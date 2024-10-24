<?php

namespace App\Models;

use App\Enums\StoryPinEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use stdClass;

class History extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'story_id',
        'chapter_number',
    ];

    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    public static function createHistory($storyId, $chapterNumber)
    {
        if (Auth::check()) {
            self::createHistoryByAuth(Auth::id(), $storyId, $chapterNumber);
        } else {
            $name = 'history_Stories';
            $time = time() + (86400 * 30);
            $data = new stdClass;
            $history = new stdClass;
            $story = Story::query()->find($storyId, [
                'id',
                'name',
                'image',
                'slug',
            ]);
            $history->story_id = $story->id;
            $history->story_name = $story->name;
            $history->story_image = $story->image_url;
            $history->story_slug = $story->slug;
            $history->chapter_number = $chapterNumber;
            if (isset($_COOKIE[$name])) {
                $data = json_decode($_COOKIE[$name]);
            }
            $data->$storyId = $history;
            setcookie($name, json_encode($data), $time, '/');
        }
    }

    public static function createHistoryByAuth($userId, $storyId, $chapterNumber)
    {
        $history = History::query()
            ->firstOrCreate([
                'user_id' => $userId,
                'story_id' => $storyId,
            ]);
        ;
        $history->chapter_number = $chapterNumber;
        $history->updated_at = Carbon::now();
        $history->save();
    }

    public static function showHistoriesByUser($userId): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return History::query()
            ->join('stories', 'histories.story_id', '=', 'stories.id')
            ->join('users', 'users.id', '=', 'stories.user_id')
            ->where('histories.user_id', $userId)
            ->whereNull('stories.deleted_at')
            ->whereNull('users.deleted_at')
            ->where('stories.pin', '>', StoryPinEnum::UPLOADING)
            ->latest('histories.updated_at')
            ->paginate(12);
    }


    public static function showHistoriesByGuest(): ?array
    {
        $name = 'history_Stories';
        if (!isset($_COOKIE[$name])) {
            return null;
        }
        $data = (array)json_decode($_COOKIE[$name]);
        return array_reverse($data);
    }

    public static function getHistoriesByGuest()
    {
        $name = 'history_Stories';
        if (!isset($_COOKIE[$name])) {
            return null;
        }
        return json_decode($_COOKIE[$name]);
    }

}
