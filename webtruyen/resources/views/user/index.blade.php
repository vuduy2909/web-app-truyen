@extends('layout.admin_and_user_page.master')
@section('main')
    @push('css')
        <style>
            .title {
                margin: 0 12px 24px;
                padding-bottom: 4px;
                border-bottom: 1px solid #aaa;
                width: fit-content;
            }
        </style>
        <style>
            .item_box {
                font-size: 17px;
                overflow: hidden;
                text-overflow: ellipsis;
                height: 360px;
                line-height: 22px;
                margin: 3px 0;
            }

            .item_box a {
                text-decoration: none;
                color: var(--color-mode);
            }

            .item_box a:hover {
                color: #288ad6;
            }

            .dark .item_box a:hover {
                color: red;
            }
            .item_box .box_image {
                border: 1px solid #ddd;
                height: 68%;
                width: 100%;
                position: relative;
            }
            .item_box img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .box_view {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                height: 40px;
                line-height: 30px;
                padding: 5px 12px;
                font-size: 16px;
                display: block;
                color: #fff;
                background-color: #000;
                opacity: .65;
            }

            .text_box {
                height: 23%;
            }

            .text_box_name {
                font-weight: 550;
                width: 100%;
                max-height: 45px;
                margin-top: 10px;
                overflow: hidden;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
            }

            .text_box_chapter_number {
                float: left;
                font-size: 14px;
            }
        </style>
    @endpush
    <div class="row">
        <div class="col-md-7">
            {{ Breadcrumbs::render('user.index') }}
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h4 class="title">Truyện đã được kiểm duyệt</h4>
        </div>
    </div>
    <div class="row">
        @foreach($approvedStories as $approvedStory)
            <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
                <div class="item_box">
                    <a href="{{ route('user.stories.show', [$approvedStory->slug, $approvedStory->id]) }}">
                        <div class="box_image">
                            <img
                                src="{{ $approvedStory->image_url }}" alt="">
                            <div class="box_view" style="display: flex; justify-content: space-around;">
                                <span><i class="fa fa-eye"></i> {{ $approvedStory->view_count }}</span>
                                <span><i class="fa fa-star-o"></i> {{ round($approvedStory->star_avg_total, 1) }}</span>
                            </div>
                        </div>
                    </a>
                    <div class="text_box">
                        <p class="text_box_name text-capitalize">
                            <a href="{{ route('user.stories.show', [$approvedStory->slug, $approvedStory->id]) }}">
                                {{ $approvedStory->name }}
                            </a>
                        </p>
                        <p class="text_box_chapter">
                            <span class="text_box_chapter_number">
                                Số chương: {{ $approvedStory->chapter_count }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col-12">
            <h4 class="title">Truyện chờ kiểm duyệt</h4>
        </div>
    </div>
    <div class="row">
        @foreach($uploadingStories as $uploadingStory)
            <div class="col-lg-2 col-sm-3 col-xs-6">
                <div class="item_box">
                    <a href="{{ route('user.stories.show', [$uploadingStory->slug, $uploadingStory->id]) }}">
                        <div class="box_image">
                            <img
                                src="{{ $uploadingStory->image_url }}" alt="">
                            <div class="box_view" style="display: flex; justify-content: space-around;">
                                {{-- <span><i class="fa fa-eye"></i> {{ $uploadingStory->view_count }}</span>
                                <span><i class="fa fa-star-o"></i> {{ $uploadingStory->totalStar }}</span> --}}
                            </div>
                        </div>
                    </a>
                    <div class="text_box">
                        <p class="text_box_name text-capitalize">
                            <a href="{{ route('user.stories.show', [$uploadingStory->slug, $uploadingStory->id]) }}">
                                {{ $uploadingStory->name }}
                            </a>
                        </p>
                        <p class="text_box_chapter">
                            <span class="text_box_chapter_number">
                                Số chương: {{ $uploadingStory->chapter_count }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-12">
            <h4 class="title">Truyện không được kiểm duyệt</h4>
        </div>
    </div>
    <div class="row">
        @foreach($notApprovedStories as $notApprovedStory)
            <div class="col-lg-2 col-sm-3 col-xs-6">
                <div class="item_box">
                    <a href="{{ route('user.stories.show', [$notApprovedStory->slug, $notApprovedStory->id]) }}">
                        <div class="box_image">
                            <img
                                src="{{ $notApprovedStory->image_url }}" alt="">
                            <div class="box_view" style="display: flex; justify-content: space-around;">
                            </div>
                        </div>
                    </a>
                    <div class="text_box">
                        <p class="text_box_name text-capitalize">
                            <a href="{{ route('user.stories.show', [$notApprovedStory->slug, $notApprovedStory->id]) }}">
                                {{ $notApprovedStory->name }}
                            </a>
                        </p>
                        <p class="text_box_chapter">
                            <span class="text_box_chapter_number">
                                Số chương: {{ $notApprovedStory->chapter_count }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-12">
            <h4 class="title">Truyện chưa đăng</h4>
        </div>
    </div>
    <div class="row">
        @foreach($editingStories as $editingStory)
            <div class="col-lg-2 col-sm-3 col-xs-6">
                <div class="item_box">
                    <a href="{{ route('user.stories.show', [$editingStory->slug, $editingStory->id]) }}">
                        <div class="box_image">
                            <img
                                src="{{ $editingStory->image_url }}" alt="">
                            <div class="box_view" style="display: flex; justify-content: space-around;">
                            </div>
                        </div>
                    </a>
                    <div class="text_box">
                        <p class="text_box_name text-capitalize">
                            <a href="{{ route('user.stories.show', [$editingStory->slug, $editingStory->id]) }}">
                                {{ $editingStory->name }}
                            </a>
                        </p>
                        <p class="text_box_chapter">
                            <span class="text_box_chapter_number">
                                Số chương: {{ $editingStory->chapter_count }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-12">
            <h4 class="title">Chương không được kiểm duyệt</h4>
        </div>
    </div>
    <div class="row">
        @foreach($chapters as $chapter)
            <div class="col-lg-2 col-sm-3 col-xs-6">
                <div class="item_box">
                    <a href="{{ route('user.stories.chapters.show', [$chapter->slug, $chapter->number]) }}">
                        <div class="box_image">
                            <img
                                src="{{ $chapter->image_url }}" alt="">
                            <div class="box_view" style="display: flex; justify-content: space-around;">
                                Chương {{ $chapter->number }}
                            </div>
                        </div>
                    </a>
                    <div class="text_box">
                        <p class="text_box_name text-capitalize">
                            <a href="{{ route('user.stories.chapters.show', [$chapter->slug, $chapter->number]) }}">
                                {{ $chapter->name }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
