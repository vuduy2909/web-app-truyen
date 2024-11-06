<?php

namespace App\Http\Controllers\HandleAPI;

use App\Enums\StoryPinEnum;
use App\Http\Controllers\Controller;
use App\Models\History;
use App\Models\Story;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    //      [GET] /api/history/list
    public function listHistories(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $histories = History::showHistoriesByUser($request->user()->id);
            if (empty($histories))
                return handleResponseAPI('Không có truyện nào trong lịch sử đọc cả');

            $newHistories = [];
            foreach ($histories as $history) {
                $newHistories[] = [
                    'name' => $history['story']['name'],
                    'image' => $history['story']['image_url'],
                    'slug' => $history['story']['slug'],
                    'chapter_number' => $history['chapter_number'],
                
                ];
            }

            return handleResponseAPI('Trả về danh sách lịch sử truyện đã đọc', true, $newHistories);
        } catch (\Throwable $e) {
            return handleResponseAPI('Có lỗi xảy ra! ' . $e->getMessage());
        }
    }

    public function showHistoryByStory(Request $request, $storySlug): \Illuminate\Http\JsonResponse
    {
        try {
            $userId = $request->user()->id;

            $history = History::query()
                ->join('stories', 'histories.story_id', '=', 'stories.id')
                ->join('users', 'users.id', '=', 'stories.user_id')
                ->where('histories.user_id', $userId)
                ->whereNull('stories.deleted_at')
                ->whereNull('users.deleted_at')
                ->where('stories.pin', '>', StoryPinEnum::UPLOADING)
                ->where('stories.slug', $storySlug)
                ->first();

            $historyResult = [
                'name' => $history['story']['name'],
                'image' => $history['story']['image_url'],
                'slug' => $history['story']['slug'],
                'chapter_number' => $history['chapter_number'],
            ];
            return handleResponseAPI('Lấy ra lịch sử của một truyện', true, $historyResult);
        } catch (\Throwable $e) {
            return handleResponseAPI('Có lỗi xảy ra! ' . $e->getMessage());
        }
    }

    //      [POST] /api/history/destroy
    public function destroyHistory(Request $request, $storySlug): \Illuminate\Http\JsonResponse
    {
        try {
            $story = Story::query()->where('slug', $storySlug)->first();
            History::query()
                ->where('user_id', $request->user()->id)
                ->where('story_id', $story->id)
                ->delete()
            ;

            return handleResponseAPI('Xóa lịch sử đọc truyện', true);
        } catch (\Throwable $e) {
            return handleResponseAPI('Có lỗi xảy ra! ' . $e->getMessage());
        }
    }
}
