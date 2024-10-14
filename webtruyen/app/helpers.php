<?php


use App\Enums\ChapterPinEnum;
use App\Enums\StoryPinEnum;
use App\Http\Controllers\HandleAPI\LinksAPIController;
use App\Models\Category;
use App\Models\Story;
use Illuminate\Database\Eloquent\Collection;

if (!function_exists('chapterList')) {
    function categoryList(): Collection|array
    {
        return Category::query()->get();
    }
}

if (!function_exists('replaceCategories')) {
    function replaceCategories($categories): array
    {
        $newCategories = [];
        foreach ($categories as $category) {
            $newCategories[] = [
                'name' => $category['name'],
                'slug' => $category['slug']
            ];
        }
        return $newCategories;
    }
}

if (!function_exists('handleResponseAPI')) {
    function handleResponseAPI($message, $status = false, $body = null): \Illuminate\Http\JsonResponse
    {
        $links = LinksAPIController::linksAPIHandle();

        return response()->json([
            "status" => $status,
            "message" => $message,
            "body" => $body,
            "links" => $links,
        ]);
    }
}


if (!function_exists('listStoriesSearch')) {
    function listStoriesSearch(): Collection|array
    {
        $stories = Story::query()
            ->with('categories')
            ->addSelect("*")
            ->selectSub("select authors.name from authors where authors.id = stories.author_id",
                'author_name')
            ->selectSub("
            select number
            from chapters
            where story_id = stories.id and pin = ". ChapterPinEnum::APPROVED ."
            order by number desc limit 1
            ", 'chapter_new')
            ->where('stories.pin', '>', StoryPinEnum::UPLOADING)
            ->inRandomOrder()
            ->get();
        $listStories = [];
        foreach ($stories as $item) {
            $story = [];
            $story['id'] = $item->id;
            $story['name'] = $item->name;
            $story['chapter_new'] = $item->chapter_new;
            $story['category_name'] = $item->categoriesName;
            $story['author'] = $item->author_name;
            $story['image'] = $item->image_url;
            $story['slug'] = $item->slug;
            $listStories[] = $story;
        }
        return $listStories;
    }
}
