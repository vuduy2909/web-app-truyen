<?php

namespace App\Http\Controllers\HandleAPI;

use App\Enums\ChapterPinEnum;
use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\History;
use App\Models\Story;
use App\Models\User;
use App\Models\View as ViewAlias;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    // [GET] /api/chapters/list/{storySlug}
    public function listChaptersByStory(Request $request, $storySlug): \Illuminate\Http\JsonResponse
    {
        try {
            $sort = $request->get('sort') === 'desc' ? 'desc' : 'asc';

            //          Tìm story từ slug
            $story = Story::query()
                ->where('slug', $storySlug)
                ->first();
            //          Nếu không có truyện thì trả về lỗi
            if (is_null($story))
                return handleResponseAPI("Không tìm thấy truyện với slug: '$storySlug'");

            //          chapters
            $chapters = Chapter::query()
                ->where('pin', ChapterPinEnum::APPROVED)
                ->where('story_id', $story->id)
                ->orderBy('number', $sort)
                ->get();

            //        cắt chapter list
            $newChapters = [];
            foreach ($chapters as $chapter) {
                $newChapters[] = [
                    'name' => $chapter->name,
                    'number' => $chapter->number,
                    'story_slug' => $storySlug,
                ];
            }

            $result = [
                'sort' => $sort === 'asc' ? 'Cũ nhất' : 'mới nhất',
                'data' => $newChapters,
            ];

            return handleResponseAPI("Danh sách chapter của truyện '$story->name'", true, $result);
        } catch (\Throwable $e) {
            return handleResponseAPI('Có lỗi xảy ra! ' . $e->getMessage());
        }
    }

    // [GET] /api/chapters/list/{storySlug}
    public function listChaptersDownLoadByStory(Request $request, $storySlug): \Illuminate\Http\JsonResponse
    {
        try {
            $perPage = $request->get("perPage") ?? 2;
            //          Tìm story từ slug
            $story = Story::query()
                ->where('slug', $storySlug)
                ->first();
            //          Nếu không có truyện thì trả về lỗi
            if (is_null($story))
                return handleResponseAPI("Không tìm thấy truyện với slug: '$storySlug'");

            //          chapters
            $chapters = Chapter::query()
                ->where('pin', ChapterPinEnum::APPROVED)
                ->where('story_id', $story->id)
                ->paginate($perPage, ['number', 'name', 'content', 'story_id']);

            return handleResponseAPI("Danh sách chapter của truyện '$story->name'", true, $chapters);
        } catch (\Throwable $e) {
            return handleResponseAPI('Có lỗi xảy ra! ' . $e->getMessage());
        }
    }

    private function checkAuth($authorization)
    {
        if (!$authorization) return null;

        $authorization = explode(' ', $authorization)[1];

        $user = User::query()->find(decrypt($authorization));
        if (is_null($user)) return null;
        if ($user->deleted_at !== null) return null;
        return $user->id;
    }

    // [GET] /api/chapters/show/{storySlug}/{number}
    public function showChapterByStory(Request $request, $storySlug, $number): \Illuminate\Http\JsonResponse
    {
        try {
            $story = Story::query()
                ->where('slug', $storySlug)
                ->first();

            //          Nếu không có truyện thì trả về lỗi
            if (is_null($story))
                return handleResponseAPI("Không tìm thấy truyện với slug: '$storySlug'");

            //        Tạo userId view
            $userIdView = $request->getClientIp();

            //        thêm vào lich sử nếu người dùng đăng nhập
            $authorization = $request->header('Authorization');
            if ($userId = $this->checkAuth($authorization)) {
                History::createHistoryByAuth($userId, $story->id, $number);

                //  Thay thế userId view bằng user id nếu có đăng nhập
                $userIdView = $userId;
            }

            //      lấy ra trang hiện tại
            $chapter = $this->getChapterByNumberAndStorySlug($storySlug, $number);

            //      Thêm vào bảng view nếu chưa có view này
            ViewAlias::createView($userIdView, $story->id, $chapter->id);

            //          chapters list
            $chapters = Chapter::query()
                ->where('pin', ChapterPinEnum::APPROVED)
                ->where('story_id', $story->id)
                ->get();

            //        cắt chapter list
            $newChapters = [];
            foreach ($chapters as $chapterItem) {
                $newChapters[] = [
                    'name' => $chapterItem->name,
                    'number' => $chapterItem->number,
                    'story_slug' => $storySlug,
                ];
            }

            //      lấy ra danh sách trang
            $chapterListNumber = Chapter::query()
                ->where('pin', ChapterPinEnum::APPROVED)
                ->where('story_id', $story->id)
                ->pluck('number')
                ->toArray();

            //        lấy ra các con trỏ trang
            $current = array_search($chapter->number, $chapterListNumber, true);
            $prev = $chapterListNumber[$current - 1] ?? null;
            $next = $chapterListNumber[$current + 1] ?? null;
            $first = $this->getChapterByNumberAndStorySlug($storySlug, reset($chapterListNumber));
            $last = $this->getChapterByNumberAndStorySlug($storySlug, end($chapterListNumber));

            //      Tạo con trỏ prev nếu nó không null
            if (!is_null($prev)) {
                $prev = $this->getChapterByNumberAndStorySlug($storySlug, $prev);
                $prev = [
                    'name' => $prev['name'],
                    'number' => $prev['number'],
                    'story_slug' => $storySlug,
                ];
            }

            //      Tạo con trỏ next nếu nó không null
            if (!is_null($next)) {
                $next = $this->getChapterByNumberAndStorySlug($storySlug, $next);
                $next = [
                    'name' => $next['name'],
                    'number' => $next['number'],
                    'story_slug' => $storySlug,
                ];
            }

            //      Tạo con trỏ đầu trang
            $first = [
                'name' => $first['name'],
                'number' => $first['number'],
                'story_slug' => $storySlug,
            ];

            //      Tạo con trỏ cuối trang
            $last = [
                'name' => $last['name'],
                'number' => $last['number'],
                'story_slug' => $storySlug,
            ];

            $result = [
                'data' => [
                    'story_name' => $story->name,
                    'name' => $chapter['name'],
                    'number' => $chapter['number'],
                    'content' => $chapter['content'],
                    'updated_at' => $chapter['updated_at'],
                ],
                'links' => [
                    'current' => [
                        'name' => $chapter['name'],
                        'number' => $chapter['number'],
                        'story_slug' => $story->slug,
                    ],
                    'pre' => $prev,
                    'next' => $next,
                    'first' => $first,
                    'last' => $last,
                ],
                'list' => $newChapters,
            ];

            return handleResponseAPI("Chương $number của truyện {$story->name}, " .
                "các con trỏ trang và danh sách chương",
                true,
                $result);

        } catch (\Throwable $e) {
            return handleResponseAPI('Có lỗi xảy ra! ' . $e->getMessage());
        }
    }

    public function getChapterByNumberAndStorySlug($storySlug, $number): object|null
    {
        $story = Story::query()
            ->where('slug', $storySlug)
            ->first();

        return Chapter::query()
            ->where('story_id', $story->id)
            ->where('pin', ChapterPinEnum::APPROVED)
            ->where('number', $number)
            ->first();
    }
}
