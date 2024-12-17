@extends('layout.front_page.master')
@section('main')
    @push('css')
        <link rel="stylesheet" href="{{ asset('front_asset/css/own/index.css') }}">
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
                height: 60%;
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
        <div class="col-md-12">
            {{ Breadcrumbs::render("show_history") }}
        </div>
    </div>
    @auth
        @if (isset($histories))
            <div class="row">
                @foreach ($histories as $history)
                    <div class="col-lg-2 col-sm-3 col-6 history_item" id="history_item-{{ $history->story_id }}">
                        <div class="item_box">
                            <a href="{{ route('show_story', $history->story->slug) }}">
                                <div class="box_image">
                                    <img src="{{ $history->story->image_url }}" alt="{{ $history->story->name }}">
                                    <div class="box_view text-center" style="z-index: 100; cursor: pointer">
                                        <a class="histories_close"
                                           data-story_id="{{ $history->story_id }}"
                                           data-history_item="history_item-{{ $history->story->id }}">
                                            Xóa
                                        </a>
                                    </div>
                                </div>
                            </a>
                            <div class="text_box">
                                <p class="text_box_name text-capitalize">
                                    <a href="{{ route('show_story', $history->story->slug) }}">
                                        {{ $history->story->name }}
                                    </a>
                                </p>
                                <p class="text_box_chapter">
                                <span class="text_box_chapter_number">
                                    <a href="{{ route('show_chapter', [$history->story->slug, $history->chapter_number]) }}">
                                        Đọc tiếp chương {{ $history->chapter_number }}
                                    </a>
                                </span>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    {{ $histories->links() }}
                </div>
            </div>
        @endif
    @endauth
    @guest
        <div class="row">
            @if (isset($histories))
                @foreach ($histories as $history)
                    <div class="col-lg-2 col-sm-3 col-6 history_item" id="history_item-{{ $history->story_id }}">
                        <div class="item_box">
                            <a href="{{ route('show_story', $history->story_slug) }}">
                                <div class="box_image">
                                    <img src="{{ $history->story_image }}" alt="{{ $history->story_name }}">
                                    <div class="box_view text-center" style="z-index: 100; cursor: pointer">
                                        <a class="histories_close"
                                           data-history_item="history_item-{{ $history->story_id }}"
                                           data-story_id="{{ $history->story_id }}"
                                        >
                                            Xóa
                                        </a>
                                    </div>
                                </div>
                            </a>
                            <div class="text_box">
                                <p class="text_box_name text-capitalize">
                                    <a href="{{ route('show_story', $history->story_slug) }}">
                                        {{ $history->story_name }}
                                    </a>
                                </p>
                                <p class="text_box_chapter">
                            <span class="text_box_chapter_number">
                                <a href="{{ route('show_chapter', [$history->story_slug, $history->chapter_number]) }}">
                                    Đọc tiếp Chương {{ $history->chapter_number }}
                                </a>
                            </span>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    @endguest

    @push('js')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                const historiesClose = $('.histories_close')
                historiesClose.each(function (index, historyClose) {
                    historyClose.onclick = (e) => {
                        let idHistoryElement = e.target.dataset.history_item
                        const historyElement = $('#' + idHistoryElement)
                        const story_id = e.target.dataset.story_id
                        submitDestroyHistory(story_id)
                        historyElement.remove()
                    }
                })

                // khi thực hiện kích vào nút Sign in
                function submitDestroyHistory(story_id) {
                    let data = {
                        "_token": "{{ csrf_token() }}",
                        'story_id': story_id,
                    }
                    //Sử dụng hàm $.ajax()
                    $.ajax({
                        type: 'POST', //kiểu post
                        url: '{{ route('history.destroy') }}', //gửi dữ liệu sang trang submit.php
                        data: data,
                        success: function (data) {
                            if ($.isEmptyObject(data.error)) {
                                console.log(data.success);
                            } else {
                                console.log(data.error);
                            }
                        }
                    });
                    return false;
                }
            });
        </script>
    @endpush
@endsection
