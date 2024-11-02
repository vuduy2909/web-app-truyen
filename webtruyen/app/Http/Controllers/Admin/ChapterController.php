<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ChapterPinEnum;
use App\Enums\StoryPinEnum;
use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Story;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ChapterController extends Controller
{

    private Builder $model;
    private string $table;
    private string $title;

    public function __construct()
    {
        $this->table = (new Chapter())->getTable();
        View::share('table', $this->table);
    }

    public function index()
    {
        $data = Chapter::query()
            ->join('stories', 'chapters.story_id', '=', 'stories.id')
            ->where('stories.pin','>', StoryPinEnum::EDITING)
            ->where('chapters.pin', ChapterPinEnum::UPLOADING)
            ->oldest('chapters.updated_at')
            ->paginate(5, ['chapters.*' ,'stories.name as story']);

        $this->title = "Những chương chờ kiểm duyệt mới";
        View::share('title', $this->title);

        return view("admin.$this->table.index", [
            'data' => $data,
        ]);
    }

    public function show($id, $number)
    {
        $story = Story::query()->find($id);
        $chapterList = Chapter::query()
            ->where('pin', '>', ChapterPinEnum::EDITING)
            ->where('story_id', $story->id)
            ->pluck('number')->toArray();

        $chapter = Chapter::query()->where('story_id', $story->id)
            ->where('number', $number)->first();

        $this->title = "Chương truyện";
        View::share('title', $this->title);


        $curent = array_search($chapter->number, $chapterList, true);
        $pre = $chapterList[$curent - 1] ?? 0;
        $next = $chapterList[$curent + 1] ?? 0;
        $first = reset($chapterList);
        $last = end($chapterList);


        return view("admin.$this->table.show", [
            'story' => $story,
            'chapterList' => $chapterList,
            'chapter' => $chapter,
            'pre' => $pre,
            'next' => $next,
            'first' => $first,
            'last' => $last,
        ]);
    }

    public function approve($id, $number)
    {
        $story = Story::query()->find($id);
        if ($story->pin < StoryPinEnum::APPROVED) {
            return redirect()->back()->with('error', 'Bạn chưa duyệt truyện này.');
        }
        $chapter = Chapter::query()->where('story_id', $story->id)
            ->where('number', $number)->first();
        $chapter->update([
            'pin' => ChapterPinEnum::APPROVED,
        ]);
        return redirect()->back()->with('success', 'Đã duyệt chương.');
    }

    public function unApprove($id, $number)
    {
        $story = Story::query()
            ->with('chapter', function ($qr) {
                $qr->where('pin', '>', ChapterPinEnum::EDITING);
            })
            ->find($id);
        if ($story->chapter->count() === 1) {
            return redirect()->back()->with('error', 'Truyện chỉ có 1 chương, bạn chỉ có thể gỡ truyện');
        }
        $chapter = Chapter::query()->where('story_id', $story->id)
            ->where('number', $number)->first();
        $chapter->update([
            'pin' => ChapterPinEnum::NOT_APPROVE,
        ]);
        return redirect()->back()->with('success', 'Đã gỡ chương.');
    }
}
