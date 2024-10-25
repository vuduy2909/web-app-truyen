<?php

namespace App\Http\Controllers\HandleAPI;

use App\Enums\StoryPinEnum;
use App\Enums\UserGenderEnum;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Story;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CommentController extends Controller
{
    public function listCommentsByStory(Request $request, $storySlug): \Illuminate\Http\JsonResponse
    {
        try {
            $comments = Comment::query()
                ->addSelect([
                    'comments.id',
                    'comments.content',
                    'comments.parent',
                    'comments.created_at',
                ])
                ->addSelect([
                    'users.id as user_id',
                    'users.name as user_name',
                    'users.avatar as user_avatar',
                ])
                ->join('stories', 'comments.story_id', '=', 'stories.id')
                ->join('users', 'users.id', '=', 'comments.user_id')
                ->whereNull('stories.deleted_at')
                ->whereNull('users.deleted_at')
                ->where('stories.pin', '>', StoryPinEnum::UPLOADING)
                ->where('stories.slug', $storySlug)
                ->whereNull('comments.parent')
                ->latest("comments.created_at")
                ->get();;

            foreach ($comments as $comment) {
                $comment->user_avatar = $this->getAvatarUrlAttribute($comment->user_avatar, $comment->user_id);
                $comment->children = $this->getCommentChildren($comment->id);
            }

            return handleResponseAPI('Trả về các comment của truyện thành công', true, $comments);
        } catch (\Throwable $e) {
            return handleResponseAPI("có lỗi xảy ra {$e->getMessage()}");
        }
    }

    public function createComment($storySlug, Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validate = Validator::make($request->all(), [
                'content' => [
                    'required',
                    'string',
                ],
                'parent' => [
                    'nullable',
                    Rule::exists(Comment::class, 'id'),
                ],
            ]);

            if ($validate->fails()) {
                $array = $validate->errors()->toArray();
                return handleResponseAPI(array_shift($array)[0]);
            }
            $story = Story::query()->where('slug', $storySlug)->first();
            if (is_null($story)) {
                return handleResponseAPI('Không tồn tại truyện này');
            }

            Comment::query()->create([
                ...$request->all(),
                'story_id' => $story->id,
                'user_id' => $request->user()->id,
            ]);
            return handleResponseAPI('Thêm bình luận thành công', true);
        } catch (\Throwable $e) {
            return handleResponseAPI("có lỗi xảy ra! {$e->getMessage()}");
        }
    }

    public function updateComment($id, Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validate = Validator::make($request->all(), [
                'content' => [
                    'required',
                    'string',
                ],
            ]);

            if ($validate->fails()) {
                $array = $validate->errors()->toArray();
                return handleResponseAPI(array_shift($array)[0]);
            }

            $comment = Comment::query()->find($id);
            if ($request->user()->id != $comment->user_id) {
                return
                    handleResponseAPI(
                        "Bạn không có quyền sửa bình luận này, user: {$request->user()->id},
                        user comment: {$comment->user_id}"
                    );
            }

            $comment->update($request->all());
            return handleResponseAPI('Sửa bình luận thành công', true);
        } catch (\Throwable $e) {
            return handleResponseAPI("có lỗi xảy ra! {$e->getMessage()}");
        }
    }

    public function deleteComment($id, Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $comment = Comment::query()->find($id);
            if ($request->user()->id !== $comment->user_id && $request->user()->level_id == 1) {
                return handleResponseAPI("Bạn không có quyền xóa bình luận này");
            }
            $this->deleteCommentAndChildren($id);
            $comment->delete();
            return handleResponseAPI('Xóa bình luận thành công', true);
        } catch (\Throwable $e) {
            return handleResponseAPI("có lỗi xảy ra! {$e->getMessage()}");
        }
    }

    public function deleteCommentAndChildren($commentId)
    {
        $commentIdsToDelete = Comment::query()->where('parent', $commentId)->pluck('id')->toArray();
        Comment::query()->whereIn('id', $commentIdsToDelete)->delete();

        foreach ($commentIdsToDelete as $childCommentId) {
            $this->deleteCommentAndChildren($childCommentId);
        }
    }

    public function getCommentChildren($id): array
    {
        $result = [];
        $comments = Comment::query()
            ->addSelect([
                'comments.id',
                'comments.content',
                'comments.parent',
                'comments.created_at',
            ])
            ->addSelect([
                'users.id as user_id',
                'users.name as user_name',
                'users.avatar as user_avatar',
            ])
            ->join('stories', 'comments.story_id', '=', 'stories.id')
            ->join('users', 'users.id', '=', 'comments.user_id')
            ->whereNull('stories.deleted_at')
            ->whereNull('users.deleted_at')
            ->where('stories.pin', '>', StoryPinEnum::UPLOADING)
            ->where('comments.parent', $id)
            ->get();

        if (is_null($comments)) return $result;

        foreach ($comments as $comment) {
            $comment->user_avatar = $this->getAvatarUrlAttribute($comment->user_avatar, $comment->user_id);
            $comment->children = $this->getCommentChildren($comment->id);
            $result[] = $comment;
        }

        return $result;
    }

    private function getAvatarUrlAttribute($avatar, $id): string
    {
        if (is_null($avatar)) {
            return asset("img/no_face.png");
        }
        return file_exists("storage/avatars/$id") ?
            asset("storage/$avatar") : $avatar;
    }
}
