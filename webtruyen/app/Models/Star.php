<?php

namespace App\Models;

use App\Enums\ChapterPinEnum;
use App\Enums\StoryPinEnum;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Star extends Model
{
    use HasFactory;
    protected $fillable = [
        'total',
        'story_id',
        'user_id',
    ];

    public static function showTopStar(): Collection|array
    {
        $starQr = Star::query()
            ->selectRaw('Round(AVG(total), 1) as totalStar, COUNT(*) as number_user, story_id')
            ->groupBy('story_id');

        $storiesRank = Story::query()
            ->select('stories.*')
            ->selectSub("
            select number
            from chapters
            where story_id = stories.id and pin = ". ChapterPinEnum::APPROVED ."
            order by number desc limit 1
            ", 'chapter_new_number')
            ->joinSub($starQr, 'stories_rank', 'id', '=', 'stories_rank.story_id')
            ->join('users', 'users.id', '=', 'stories.user_id')
            ->whereNull('users.deleted_at')
            ->where('pin', '>', StoryPinEnum::UPLOADING)
            ->orderBy('totalStar', 'desc')
            ->limit(3)
            ->get();
        ;
        return $storiesRank;
    }
}
