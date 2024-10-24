<?php

namespace App\Http\Controllers\User;

use App\Enums\ChapterPinEnum;
use App\Enums\StoryPinEnum;
use App\Enums\UserGenderEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateRequest;
use App\Models\Chapter;
use App\Models\Star;
use App\Models\Story;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    public function index()
    {
        $starQr = Star::query()
            ->groupBy('story_id');

        $approvedStories = Story::query()
            ->select('*')
            ->withCount('view')
            ->selectSub("
            select count(number)
            from chapters
            where story_id = stories.id and pin = ". ChapterPinEnum::APPROVED ."
            order by number desc limit 1
            ", 'chapter_count')
                ->withAvg('star', 'total')
            ->where('pin', '>', StoryPinEnum::UPLOADING)
            ->where('user_id', Auth::id())
            ->groupBy('stories.id')
            ->latest()
            ->get()
        ;

        $uploadingStories = Story::query()
            ->select('*')
            ->selectSub("
            select count(number)
            from chapters
            where story_id = stories.id
            order by number desc limit 1
            ", 'chapter_count')
            ->where('pin', StoryPinEnum::UPLOADING)
            ->where('user_id', Auth::id())
            ->groupBy('stories.id')
            ->latest()
            ->get()
        ;

        $editingStories = Story::query()
            ->select('*')
            ->selectSub("
            select count(number)
            from chapters
            where story_id = stories.id
            order by number desc limit 1
            ", 'chapter_count')
            ->where('pin', StoryPinEnum::EDITING)
            ->where('user_id', Auth::id())
            ->groupBy('stories.id')
            ->latest()
            ->get()
        ;

        $notApprovedStories = Story::query()
            ->select('*')
            ->selectSub("
            select count(number)
            from chapters
            where story_id = stories.id
            order by number desc limit 1
            ", 'chapter_count')
            ->where('pin', StoryPinEnum::NOT_APPROVE)
            ->where('user_id', Auth::id())
            ->groupBy('stories.id')
            ->latest()
            ->get()
        ;

        $chapters = Story::query()
            ->join('chapters', 'stories.id', '=', 'chapters.story_id')
            ->where('chapters.pin', '=', ChapterPinEnum::NOT_APPROVE)
            ->where('user_id', Auth::id())
            ->oldest('chapters.updated_at')
            ->get(['stories.*', 'chapters.number'])
        ;

        View::share('title', 'Tổng hợp truyện');
        return view('user.index', [
            'approvedStories' => $approvedStories,
            'uploadingStories' => $uploadingStories,
            'notApprovedStories' => $notApprovedStories,
            'editingStories' => $editingStories,
            'chapters' => $chapters,
        ]);
    }
    public function edit()
    {
        $user = Auth::user();

        //        gender
        $genderEnum = UserGenderEnum::getValues();
        $gender = [];
        foreach ($genderEnum as $item) {
            $gender[$item] = UserGenderEnum::getNameByValue($item);
        }

        $approvedStories = Story::query()
            ->select('*')
            ->withCount('view')
            ->selectSub("
            select count(number)
            from chapters
            where story_id = stories.id and pin = ". ChapterPinEnum::APPROVED ."
            order by number desc limit 1
            ", 'chapter_count')
            ->withAvg('star', 'total')
            ->where('pin', '>', StoryPinEnum::UPLOADING)
            ->where('user_id', Auth::id())
            ->groupBy('stories.id')
            ->latest('updated_at')
            ->get()
        ;
        return view("user.info.edit", [
            'user' => $user,
            'gender' => $gender,
            'approvedStories' => $approvedStories,
        ]);
    }

    public function deleteAvatar(): \Illuminate\Http\JsonResponse
    {
        try {
            if (!(is_null(Auth::user()->avatar) || str_contains(Auth::user()->avatar, 'https'))) {
                unlink("storage/" . Auth::user()->avatar);
            }
            User::query()->find(Auth::id())->update(['avatar' => null]);
            Auth::login(Auth::user());
            return response()->json(['success' => 'Đã xóa ảnh']);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'có lỗ! ' . $e->getMessage()]);
        }
    }

    public function update(UpdateRequest $request)
    {
        $user = User::query()->find(Auth::id());

        $user->update($request->validated());
        Auth::login($user);
        return redirect()->back()
            ->with('success', 'Đã sửa thành công');
    }

    public function changeAvatar(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $validateFile = Validator::make($request->all(), [
                'image_new' => [
                    'mimes:jpeg,jpg,png,gif',
                    'required',
                ]
            ]);
            if ($validateFile->fails())
                return redirect()->back()->with('error', $validateFile->errors());

            $user = User::query()->find(Auth::id());
            $userAvatar = $user->avatar;

            if (!(is_null($userAvatar) || str_contains($userAvatar, 'https')))
                unlink("storage/$userAvatar");

            $fileAvatarExtension = $request->file('image_new')->extension();
            $fileAvatarName = "avatar.$fileAvatarExtension";
            $fileAvatarUrl = Storage::disk('public')
                ->putFileAs("avatars/$user->id",
                    $request->file('image_new'),
                    $fileAvatarName
                );

            $user->update([
                'avatar' => $fileAvatarUrl,
            ]);

            return redirect()->back()
                ->with('success', 'Đã sửa thành công');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
