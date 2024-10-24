@extends('layout.admin_and_user_page.master')
@section('main')
    @push('css')
        <style>
            .button_edit {
                position: fixed;
                right: 5rem;
                top: 15rem;
                z-index: 1000;
            }

            .button_edit a i {
                font-size: 3rem;
            }
            .button_edit button {
                margin-left: 12px;
            }

            .story_name h2 {
                font-size: 2.4rem;
                margin: 0 0 5px;
                line-height: 35px;
                text-transform: uppercase;
                font-family: "Roboto Condensed", Tahoma, sans-serif;
                text-align: center;
            }

            .chapter_name {
                font-size: 1.6rem;
                margin: 0 0 5px;
                line-height: 35px;
                text-align: center;
            }

            hr.chapter-start {
                background: url(//static.8cache.com/img/spriteimg_new_white_op.png) -200px -27px no-repeat;
                width: 59px;
                height: 20px;
            }

            hr.chapter-end {
                background: url(//static.8cache.com/img/spriteimg_new_white_op.png) 0 -51px no-repeat;
                width: 277px;
                height: 35px;
            }

            .button_box {
                margin: 24px auto;
                width: fit-content;
                display: flex;
                flex-wrap: wrap;
            }

            .dropdown,
            .dropup {
                margin: 0 8px;
            }

            .content {
                max-width: 1000px;
                margin: auto;
            }

            .content p {
                text-align: justify;
                font-size: 2.2rem;
            }

            @media screen and (max-width: 63.9375em) {
                .button_box .btn-wd {
                    min-width: fit-content;
                    padding: 8px 8px;
                }
                .button_edit {
                    flex-direction: row-reverse;
                    right: 8px;
                    top: 14rem;
                }
            }
        </style>
    @endpush
    <div class="button_box button_edit">
        <a rel="tooltip" class="btn btn-simple btn-warning btn-icon edit"
           href="{{ route("user.stories.$table.edit", [$story->slug, $chapter->id]) }}"
           data-original-title="Thay đổi thông tin chương">
            <i class="fa fa-edit"></i>
        </a>
        <form
            action='{{ route("user.stories.chapters.upload", ['slug' => $story->slug, 'id' => $chapter->id]) }}'
            method="post" >
            @csrf
            <button style="background: transparent; border: none;">
                <div class="checkbox" style="margin-top: 8px;">
                    <input type="checkbox" id="pin-{{ $story->id }}"
                           @if($chapter->pin > \App\Enums\ChapterPinEnum::EDITING)
                               checked
                        @endif
                    >
                    <label data-original-title="Đăng lên" rel="tooltip"
                           for="pin-{{ $story->id }}"></label>
                </div>
            </button>
        </form>
    </div>
    <div class="row">
        <div class="col-md-12">
            {{ Breadcrumbs::render('user.chapter.show', $story, $chapter) }}
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="story_name">
                <h2 class="text-info">{{ $story->name }}</h2>
            </div>
            <div class="chapter_name">
                <span>Chương </span> {{ $chapter->number }}: {{ $chapter->name }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <hr class="chapter-start">
            <div class="button_box">
                <a href="{{ route("user.stories.$table.show", ['slug' => $story->slug, 'number' => $chapter->number - 1]) }}"
                   class="btn btn-info btn-fill btn-wd @if ($chapter->number === 1) disabled @endif">
                    Chương trước</a>
                <div class="dropdown">
                    <button class="btn btn-info btn-fill dropdown-toggle" type="button" data-toggle="dropdown">Chọn
                        chương
                        <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        @foreach ($chapterList as $chapterItem)
                            <li @if ($chapterItem === $chapter->number) class="active disabled" @endif><a
                                        href="{{ route("user.stories.$table.show", ['slug' => $story->slug, 'number' => $chapterItem]) }}">Chương
                                    {{ $chapterItem }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <a href="{{ route("user.stories.$table.show", ['slug' => $story->slug, 'number' => $chapter->number + 1]) }}"
                   class="btn btn-info btn-fill btn-wd @if ($chapter->number === count($chapterList)) disabled @endif">Chương
                    sau
                </a>
            </div>
            <hr class="chapter-end">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="content">
                {!! $chapter->content !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <hr class="chapter-end">
            <div class="button_box">
                <a href="{{ route("user.stories.$table.show", ['slug' => $story->slug, 'number' => $chapter->number - 1]) }}"
                   class="btn btn-info btn-fill btn-wd @if ($chapter->number === 1) disabled @endif">
                    Chương trước</a>
                <div class="dropup">
                    <button class="btn btn-info btn-fill dropdown-toggle" type="button" data-toggle="dropdown">Chọn
                        chương
                        <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        @foreach ($chapterList as $chapterItem)
                            <li @if ($chapterItem === $chapter->number) class="active disabled" @endif><a
                                        href="{{ route("user.stories.$table.show", ['slug' => $story->slug, 'number' => $chapterItem]) }}">Chương
                                    {{ $chapterItem }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <a href="{{ route("user.stories.$table.show", ['slug' => $story->slug, 'number' => $chapter->number + 1]) }}"
                   class="btn btn-info btn-fill btn-wd @if ($chapter->number === count($chapterList)) disabled @endif">Chương
                    sau
                </a>
            </div>
        </div>
    </div>
@endsection
