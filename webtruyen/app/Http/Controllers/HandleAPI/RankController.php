<?php

namespace App\Http\Controllers\HandleAPI;

use App\Enums\StoryPinEnum;
use App\Http\Controllers\Controller;
use App\Models\Star;
use App\Models\Story;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RankController extends Controller
{
    //      [GET] /api/ranks/show-stars/{storySlug}
    public function showStarsByStory($storySlug): \Illuminate\Http\JsonResponse
    {
        try {
            $story = Story::query()->where('slug', $storySlug)->first();
            $starsAvg = Star::query()
                ->where('story_id', $story->id)
                ->avg('total');
            $personCount = Star::query()
                ->where('story_id', $story->id)
                ->count();

            $stars = [
                'stars_avg' => round($starsAvg, 1),
                'person_count' => $personCount
            ];

            return handleResponseAPI('Trả về trung bình sao và số người bình chọn', true, $stars);
        } catch (\Throwable $e) {
            return handleResponseAPI('Có lỗi xảy ra', $e->getMessage());
        }
    }

    //      [GET] /api/ranks/list-top-stars
    public function listTopStars(): \Illuminate\Http\JsonResponse
    {
        try {
            $starQr = Star::query()
                ->selectRaw('Round(AVG(total), 1) as totalStar, COUNT(*) as number_user, story_id')
                ->groupBy('story_id');
            $stories = Story::query()
                ->select(['stories.*', 'stories_rank.*'])
                ->withCount('chapter')
                ->joinSub($starQr, 'stories_rank', 'id', '=', 'stories_rank.story_id')
                ->join('users', 'users.id', '=', 'stories.user_id')
                ->where('pin', '>', StoryPinEnum::UPLOADING)
                ->whereNull('users.deleted_at')
                ->latest('totalStar')
                ->paginate(10)
                ->toArray();

            $newStoriesData = (new StoryController())->replaceRenderStoriesList($stories['data']);

            foreach ($stories['data'] as $key => $storyData) {
                $newStoriesData[$key] = [
                    'stars' => [
                        'avg' => $storyData['totalStar'],
                        'person_count' => $storyData['number_user']
                    ],
                    ...$newStoriesData[$key]
                ];
            }
            $stories['data'] = $newStoriesData;

            return handleResponseAPI(
                'Trả về danh sách truyện có lượt đánh giá từ cao đến thấp',
                true,
                $stories
            );
        } catch (\Throwable $e) {
            return handleResponseAPI('Có lỗi xảy ra! ' . $e->getMessage());
        }
    }

    //      [GET] /api/ranks/list-top-views
    public function listTopViews(): \Illuminate\Http\JsonResponse
    {
        try {
            $stories = Story::query()
                ->select('stories.*')
                ->withCount('view')
                ->withCount('chapter')
                ->join('users', 'users.id', '=', 'stories.user_id')
                ->where('pin', '>', StoryPinEnum::UPLOADING)
                ->whereNull('users.deleted_at')
                ->latest('view_count')
                ->paginate(10)
                ->toArray();

            $newStoriesData = (new StoryController())->replaceRenderStoriesList($stories['data']);
            foreach ($stories['data'] as $key => $storyDataItem) {
                $newStoriesData[$key] = [
                    'view_count' => $storyDataItem['view_count'],
                    ...$newStoriesData[$key]
                ];
            }

            $stories['data'] = $newStoriesData;

            return handleResponseAPI(
                'Trả về danh sách truyện có lượt view từ cao đến thấp',
                true,
                $stories
            );
        } catch(\Throwable $e) {
            return handleResponseAPI('Có lỗi xảy ra! ' . $e->getMessage());
        }
    }

    private function checkAuth($authorization)
    {
        if (!$authorization) return null;

        $authorization = explode(' ', $authorization)[1];

        $user = User::query()->find(decrypt($authorization));
        if(is_null($user)) return null;
        if ($user->deleted_at !== null) return null;
        return $user->id;
    }

    //      [POST] /api/ranks/create-stars/{storySlug}
    public function createStars(Request $request, $slugStory): \Illuminate\Http\JsonResponse
    {
        try {
            //      Lấy ra user id
            $user_id = $request->getClientIp();

            $authorization = $request->header('Authorization');

            if ($id = $this->checkAuth($authorization)) {
                $user_id = $id;
            }
            $starsValidator = Validator::make($request->all(), [
                'stars' => [
                    'required',
                    'numeric',
                    'max:5',
                    'min:1'
                ]
            ]);

            if ($starsValidator->fails()) {
                return handleResponseAPI('có lỗi', false, $starsValidator->errors());
            }

            $story = Story::query()->where('slug', $slugStory)->first();

            $star = Star::query()->where('user_id', $user_id)
                ->where('story_id', $story->id)
                ->first();
            if (isset($star))
                return handleResponseAPI('Bạn đã đánh giá rồi', false, $user_id);

            Star::query()->create([
                'user_id' => $user_id,
                'story_id' => $story->id,
                'total' => $request->get('stars'),
            ]);

            return handleResponseAPI('Bạn đã đánh giá thành công', true);
        } catch (\Throwable $e) {
            return handleResponseAPI('có lỗi xảy ra! ' . $e->getMessage());
        }
    }
}
