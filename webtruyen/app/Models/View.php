<?php

namespace App\Models;

use App\Enums\ChapterPinEnum;
use App\Enums\StoryPinEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class View extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'story_id',
        'chapter_id'
    ];


    public static function createView($user_id, $story_id, $chapter_id)
    {
        View::query()
            ->firstOrCreate(
                [
                    'user_id' => $user_id,
                    'story_id' => $story_id,
                    'chapter_id' => $chapter_id
                ],
                [
                    'user_id' => $user_id,
                    'story_id' => $story_id,
                    'chapter_id' => $chapter_id
                ]
            );
    }

    public static function showTopViewMonth(): Collection|array
    {
        $expDate = Carbon::now()->subDays(30);

        $topViewMonthQuery = View::query()->selectRaw('COUNT(*) as view_number, story_id')
            ->whereDate('created_at', '>',$expDate)
            ->groupBy('story_id')
        ;

        return Story::query()
            ->select('stories.*')
            ->selectSub("
            select number
            from chapters
            where story_id = stories.id and pin = ". ChapterPinEnum::APPROVED ."
            order by number desc limit 1
            ", 'chapter_new_number')
            ->joinSub($topViewMonthQuery, 'rank_view', 'id', '=', 'rank_view.story_id')
            ->join('users', 'users.id', '=', 'stories.user_id')
            ->whereNull('users.deleted_at')
            ->where('pin', '>', StoryPinEnum::UPLOADING)
            ->orderBy('view_number', 'desc')
            ->limit(3)
            ->get();
    }

    public static function showTopViewWeek(): Collection|array
    {
        $expDate = Carbon::now()->subDays(7);

        $topViewMonthQuery = View::query()->selectRaw('COUNT(*) as view_number, story_id')
            ->whereDate('created_at', '>',$expDate)
            ->groupBy('story_id')
        ;

        return Story::query()
            ->select('stories.*')
            ->selectSub("
            select number
            from chapters
            where story_id = stories.id and pin = ". ChapterPinEnum::APPROVED ."
            order by number desc limit 1
            ", 'chapter_new_number')
            ->joinSub($topViewMonthQuery, 'rank_view', 'id', '=', 'rank_view.story_id')
            ->join('users', 'users.id', '=', 'stories.user_id')
            ->whereNull('users.deleted_at')
            ->where('pin', '>', StoryPinEnum::UPLOADING)
            ->orderBy('view_number', 'desc')
            ->limit(3)
            ->get();
    }

    public static function showTopViewDay(): Collection|array
    {
        $expDate = Carbon::now()->subDays();

        $topViewMonthQuery = View::query()->selectRaw('COUNT(*) as view_number, story_id')
            ->whereDate('created_at', '>',$expDate)
            ->groupBy('story_id')
        ;

        return Story::query()
            ->select('stories.*')
            ->selectSub("
            select number
            from chapters
            where story_id = stories.id and pin = ". ChapterPinEnum::APPROVED ."
            order by number desc limit 1
            ", 'chapter_new_number')
            ->joinSub($topViewMonthQuery, 'rank_view', 'id', '=', 'rank_view.story_id')
            ->join('users', 'users.id', '=', 'stories.user_id')
            ->whereNull('users.deleted_at')
            ->where('pin', '>', StoryPinEnum::UPLOADING)
            ->orderBy('view_number', 'desc')
            ->limit(3)
            ->get();
    }

    public static function showTopViewAll()
    {
        return Story::query()
            ->select('stories.*')
            ->selectSub("
            select number
            from chapters
            where story_id = stories.id and pin = ". ChapterPinEnum::APPROVED ."
            order by number desc limit 1
            ", 'chapter_new_number')
            ->selectSub("
            select updated_at
            from chapters
            where story_id = stories.id and pin = ". ChapterPinEnum::APPROVED ."
            order by number desc limit 1
            ", 'chapter_new_time')
            ->join('users', 'users.id', '=', 'stories.user_id')
            ->whereNull('users.deleted_at')
            ->withCount('view')
            ->withAvg('star', 'total')
            ->where('pin', '>', StoryPinEnum::UPLOADING)
            ->orderBy('view_count', 'desc')
            ->paginate(12);
    }
}
