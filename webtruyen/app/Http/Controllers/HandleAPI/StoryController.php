<?php

namespace App\Http\Controllers\HandleAPI;

use App\Enums\ChapterPinEnum;
use App\Enums\StoryPinEnum;
use App\Enums\StoryStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Chapter;
use App\Models\Story;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    // [GET] /api/stories/new
    public function listStories(Request $request): \Illuminate\Http\JsonResponse
    {
        $q = $request->get('q');

        // lấy ra các truyện theo thứ tự mới đến cũ
        $stories = Story::query()
            ->select('stories.*')
            ->withCount('chapter')
            ->join('users', 'users.id', '=', 'stories.user_id')
            ->where('pin', '>', StoryPinEnum::UPLOADING)
            ->where('stories.name', 'like', "%$q%")
            ->whereNull('users.deleted_at')
            ->latest('stories.updated_at')
            ->paginate(10)
            ->toArray();

        if (empty($stories['data']))
            return handleResponseAPI('Không tải được truyện');

        $stories['data'] = $this->replaceRenderStoriesList($stories['data']);

        return handleResponseAPI('Danh sách truyện từ mới đến cũ', true, $stories);
    }

    // [GET] /api/stories/pin
    public function pinStories(): \Illuminate\Http\JsonResponse
    {
        $storiesPin = Story::query()
            ->select('stories.*')
            ->withCount('chapter')
            ->join('users', 'users.id', '=', 'stories.user_id')
            ->where('pin', '=', StoryPinEnum::PINNED)
            ->whereNull('users.deleted_at')
            ->inRandomOrder()
            ->limit(10)
            ->get();

//        Cắt bớt thuộc tính để thay đổi đầu ra
        $newStoriesPin = [];
        foreach ($storiesPin as $story) {
            $newStoriesPin[] = [
                'name' => $story['name'],
                'image' => $story['image_url'],
                'chapter' => [
                    'is_full' => StoryStatusEnum::checkStatusByValue($story['status']),
                    'count' => $story['chapter_count'],
                ],
                'slug' => $story['slug'],
            ];
        }

        return handleResponseAPI('Hiển thị tối đa 10 truyện được ghim ngẫu nhiên',
            true,
            $newStoriesPin);
    }

    // [GET] /api/stories/show/{slug}
    public function showStory($slug): \Illuminate\Http\JsonResponse
    {
        try {
//        story
            $story = Story::query()
                ->withCount('view')
                ->withCount('chapter')
                ->with('author')
                ->where('slug', $slug)
                ->where('pin', '>', StoryPinEnum::UPLOADING)
                ->first();

            //      thay đổi story
            $newStory = [
                'name' => $story['name'],
                'chapter' => [
                    'is_full' => StoryStatusEnum::checkStatusByValue($story['status']),
                    'count' => $story['chapter_count'],
                ],
                'view_count' => $story['view_count'],
                'image' => $story['image_url'],
                'descriptions' => $story['descriptions'],
                'slug' => $story['slug'],
                'author' => [
                    'id' => $story['author']['id'],
                    'name' => $story['author']['name'],
                ],
                'categories' => replaceCategories($story['categories']),
            ];

            return handleResponseAPI('Hiển thị thông tin truyện', true, $newStory);
        } catch (\Exception $e) {
            return handleResponseAPI('Có lỗi xảy ra! ' . $e->getMessage());
        }
    }

    // [GET] /api/stories/search
    public function searchStories(Request $request): \Illuminate\Http\JsonResponse
    {
        $q = $request->get('q');

        // lấy ra các truyện theo thứ tự mới đến cũ
        $stories = Story::query()
            ->select('stories.*')
            ->withCount('chapter')
            ->with('author')
            ->join('users', 'users.id', '=', 'stories.user_id')
            ->where('pin', '>', StoryPinEnum::UPLOADING)
            ->whereNull('users.deleted_at')
            ->where('stories.name', 'like', "%$q%")
            ->inRandomOrder()
            ->get()
            ->toArray()
        ;

        if (empty($stories))
            return handleResponseAPI("Không có truyện nào có tên '$q'");

        $newStories = [];
        foreach ($stories as $story) {
            $newStories[] = [
                'name' => $story['name'],
                'image' => $story['image_url'],
                'chapter' => [
                    'is_full' => StoryStatusEnum::checkStatusByValue($story['status']),
                    'count' => $story['chapter_count'],
                ],
                'author' => $story['author']['name'],
                'categories' => $story['categories_name'],
                'slug' => $story['slug'],
            ];
        }
        return handleResponseAPI("Lấy ra danh sách những truyện với tên '$q'", true, $newStories);
    }

    // [GET] /api/stories/tool-advanced-search
    public function toolAdvancedSearch(): \Illuminate\Http\JsonResponse
    {
        //        categories
        $categories = Category::query()->get();

        //        status
        $statusEnum = StoryStatusEnum::getValues();
        $status = [];
        foreach ($statusEnum as $item) {
            $status[$item] = StoryStatusEnum::getNameByValue($item);
        }

        //        author
        $authors = Story::query()->selectRaw("DISTINCT authors.id, authors.name")
            ->join('authors', 'stories.author_id', '=', 'authors.id')
            ->where('pin', '>', StoryPinEnum::UPLOADING)
            ->get()
            ->toArray()
        ;

        $newAuthors = [];
        foreach ($authors as $author) {
            $newAuthors[] = [
                'id' => $author['id'],
                'name' => $author['name'],
            ];
        }

        $toolAds = [
            'categories' => replaceCategories($categories),
            'status' => $status,
            'authors' => $newAuthors,
        ];

        return handleResponseAPI('Không có lỗi gì cả', true, $toolAds);
    }

    // [GET] /api/stories/advanced-search
    public function advancedSearchStories(Request $request): \Illuminate\Http\JsonResponse
    {
        $categoriesFilter = $request->get('categories');
        $statusFilter = $request->get('status');
        $authorFilter = $request->get('author');

        $query = Story::query()
            ->select('stories.*')
            ->with('categories')
            ->with('author')
            ->withCount('chapter')
            ->join('users', 'users.id', '=', 'stories.user_id')
            ->where('pin', '>',StoryPinEnum::UPLOADING)
            ->whereNull('users.deleted_at')
        ;


        if (isset($categoriesFilter)) {
            foreach($categoriesFilter as $category) {
                $query = $query->whereHas('categories', function($qr) use ($category) {
                    $qr->where('categories.id', $category);
                });
            }
        }

        if (isset($statusFilter) && $statusFilter !== 'All') {
            $query = $query->where('status', $statusFilter);
        }

        if (isset($authorFilter) && $authorFilter !== 'All') {
            $query = $query->where('author_id', $authorFilter);
        }

        $stories = $query->latest('stories.updated_at')
            ->paginate(10)->toArray();

        if (empty($stories['data']))
            return handleResponseAPI('Không có truyện với những thông tin tìm kiếm này');

        $stories['data'] = $this->replaceRenderStoriesList($stories['data']);

        return handleResponseAPI('Không có lỗi gì cả', true, $stories);
    }

    /**
     * @param array $stories
     * @return array
     */
    public function replaceRenderStoriesList(array $stories): array
    {
//        Set hiển thị ngôn ngữ tiếng việt
        Carbon::setLocale('vi');

        $newStoriesData = [];
        foreach ($stories as $story) {
            $newStoriesData[] = [
                'name' => $story['name'],
                'image' => $story['image_url'],
                'chapter' => [
                    'is_full' => StoryStatusEnum::checkStatusByValue($story['status']),
                    'count' => $story['chapter_count'],
                ],
                'categories' => $story['categories_name'],
                'slug' => $story['slug'],
                'updated_at' => Carbon::create($story['updated_at'])->diffForHumans(Carbon::now()),
            ];
        }
        return $newStoriesData;
    }
}
