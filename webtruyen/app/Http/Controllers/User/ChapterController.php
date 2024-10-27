<?php

namespace App\Http\Controllers\User;

use App\Enums\ChapterPinEnum;
use App\Enums\StoryPinEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chapter\StoreRequest;
use App\Http\Requests\Chapter\UpdateRequest;
use App\Models\Chapter;
use App\Models\Story;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class ChapterController extends Controller
{

    private Builder $model;
    private string $table;
    private string $title;

    public function __construct()
    {
        $this->model = Chapter::query();
        $this->table = (new Chapter())->getTable();
        View::share('table', $this->table);
    }

    public function index()
    {
        $data = Chapter::query()
            ->join('stories', 'chapters.story_id', '=', 'stories.id')
            ->where('stories.user_id', Auth::id())
            ->where('chapters.pin', ChapterPinEnum::NOT_APPROVE)
            ->oldest('chapters.updated_at')
            ->paginate(5, ['chapters.*' ,'stories.name as story', 'stories.slug']);

        $this->title = "Những chương không được kiểm duyệt";
        View::share('title', $this->title);

        return view("user.$this->table.index", [
            'data' => $data,
        ]);
    }

    public function show($slug, $number)
    {
        $story = Story::query()->where('slug', $slug)->first();

        $chapterList = $this->model->where('story_id', $story->id)->pluck('number')->toArray();

        $chapter = $this->model->where('story_id', $story->id)
        ->where('number', $number)->first();
        $this->title = "Chương truyện";
        View::share('title', $this->title);

        return view("user.$this->table.show", [
            'story' => $story,
            'chapterList' => $chapterList,
            'chapter' => $chapter,
        ]);
    }

    public function create($slug)
    {
        $story = Story::query()->where('slug', $slug)->first();

        $this->title = "Thêm 1 chương cho truyện: $story->name";
        View::share('title', $this->title);

        return view("user.$this->table.create", [
            'story' => $story,
        ]);
    }

    public function store(StoreRequest $request)
    {
        $number = ($this->model
            ->where('story_id', $request->get('story_id'))
            ->max('number')) ?? 0;
        ;
        $data = $request->validated();
        $data['number'] = $number + 1;
        $this->model->create($data);
        $story = Story::query()->find($request->get('story_id'));
        return redirect()->route("user.stories.show", [$story->slug, $story->id])
            ->with('success', 'Đã thêm 1 chương mới');
    }

    public function edit($slug,Chapter $id)
    {
        $story = Story::query()->where('slug', $slug)->first();

        if (Auth::id() !== $story->user_id) {
            return redirect()->back()->with('error', 'Bạn không có quyền');
        }

        $this->title = "Sửa chương truyện: $id->name";
        View::share('title', $this->title);

        return view("user.$this->table.edit", [
            'story' => $story,
            'chapter' => $id,
        ]);
    }

    public function update(UpdateRequest $request, $slug, $id)
    {
        $chapter = $this->model->find($id);

        $chapter->update($request->validated());
        return redirect()->route("user.stories.chapters.show", [$slug, $chapter->number])
                ->with('success', 'Đã sửa thành công');
    }

    public function destroy($slug, $number)
    {
        $story = Story::query()->where('slug', $slug)->first();

        $this->model->where('story_id', $story->id)
            ->where('number', $number)->delete();

        DB::statement("UPDATE chapters SET NUMBER = NUMBER - 1 WHERE story_id = $story->id AND NUMBER > $number");

        return redirect()->route("user.stories.show", [$story->slug, $story->id])
            ->with('success', 'Đã xóa chương');
    }

    public function upload($slug, $id)
    {
        $chapter = $this->model->find($id);

        $story = Story::query()->where('slug', $slug)->first();

        if ($chapter->pin > ChapterPinEnum::EDITING) {
            $chapter->update([
                'pin' => ChapterPinEnum::EDITING,
            ]);
            return redirect()->back()->with('success', 'đã gỡ chương thành công');
        }

        if ($chapter->number > 1) {
            $chapterPre = Chapter::query()->where('story_id', $story->id)
                ->where('number', $chapter->number - 1)
                ->first();

            if ($chapterPre->pin < ChapterPinEnum::UPLOADING) {
                return redirect()->back()->with('error', 'Bạn chưa đăng chương trước đó nên không được đăng chương này');
            }
        }

        if (Auth::user()->level_id > 1) {
            $chapter->update([
                'pin' => ChapterPinEnum::APPROVED,
            ]);
            return redirect()->back()->with('success', 'đã đăng chương thành công');
        }

        $chapter->update([
            'pin' => ChapterPinEnum::UPLOADING,
        ]);
        return redirect()->back()->with('success', 'đã đăng chương thành công');
    }

    public function uploadAll($slug)
    {
        $story = Story::query()->withCount('chapter')
            ->where('slug', $slug)->first();

        if ($story->chapter_count === 0) {
            return redirect()->back()->with('error', 'Không có chương');
        }

        if (Auth::user()->level_id > 1) {
            $this->model->where('story_id', $story->id)
                ->update([
                    'pin' => ChapterPinEnum::APPROVED,
                ]);
            return redirect()->back()->with('success', 'đã đăng tất cả các chương');
        }

        $this->model->where('story_id', $story->id)
            ->where('pin', '<', ChapterPinEnum::UPLOADING)
            ->update([
            'pin' => ChapterPinEnum::UPLOADING,
        ]);
        return redirect()->back()->with('success', 'đã đăng tất cả các chương');
    }
}
