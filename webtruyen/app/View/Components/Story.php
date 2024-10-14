<?php

namespace App\View\Components;

use Carbon\Carbon;
use Illuminate\View\Component;

class Story extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $name;
    public $viewCount;
    public $chapterNumber;
    public $chapterTime;
    public $image;
    public $slug;
//    public $chapterNew;
    public function __construct($story)
    {
        Carbon::setLocale('vi');
//        dd($story);
        $this->name = strtolower($story->name);
        $this->viewCount = $story->view_count;
        $this->image = $story->image_url;
        $this->slug = $story->slug;
//      Chương
        $this->chapterNumber = $story->chapter_new_number;
        $this->chapterTime = Carbon::make($story->chapter_new_time)->diffForHumans(Carbon::now());
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.story');
    }
}
