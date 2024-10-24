<?php

namespace App\Http\Controllers\HandleAPI;

use App\Enums\ChapterPinEnum;
use App\Enums\StoryPinEnum;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Story;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //  [GET] /api/categories/list
    public function listCategory(): \Illuminate\Http\JsonResponse
    {
        //        categories
        $categories = Category::query()->get();
        $categories = replaceCategories($categories);
        return handleResponseAPI('Danh sách tất cả những thể loại', true, $categories);
    }

    //  [GET] /api/categories/show
    public function showStoriesByCategory($slug): \Illuminate\Http\JsonResponse
    {
        $stories = Story::query()
            ->select([
                'stories.*'
            ])
            ->withCount('chapter')
            ->join('users', 'users.id', '=', 'stories.user_id')
            ->join('category_story as cs', 'cs.story_id', '=', 'stories.id')
            ->join('categories as c', 'c.id', '=', 'cs.category_id')
            ->where('c.slug', $slug)
            ->where('stories.pin', '>', StoryPinEnum::UPLOADING)
            ->whereNull('users.deleted_at')
            ->latest('updated_at')
            ->paginate(10)
            ->toArray()
        ;

        if (empty($stories['data']))
            return handleResponseAPI('chưa có truyện nào với thể loại này');

        $stories['data'] = (new StoryController())->replaceRenderStoriesList($stories['data']);

        $category = Category::query()->where('slug', $slug)->first();

        $storiesByCategory = [
            'stories' => $stories,
            'category' => [
                'name' => $category['name'],
                'descriptions' => $category['descriptions'],
            ]
        ];

        return handleResponseAPI("Thông tin Thể loại '{$category['name']}' và danh sách truyện có thể loại này",
            true,
            $storiesByCategory
        );
    }
}
