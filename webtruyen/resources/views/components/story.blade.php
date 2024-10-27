@push('css')
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

        .text_box_chapter_time {
            float: right;
            font-style: italic;
            font-size: 14px;
            color: rgb(180, 178, 178);
        }

    </style>
@endpush
<div class="item_box">
    <a href="{{ route('show_story', $slug) }}">
        <div class="box_image">
            <img
                src="{{ $image }}" alt="">
            <div class="box_view">
                <i class="fa-regular fa-eye mr-1"></i>{{ $viewCount }} lượt xem
            </div>
        </div>
    </a>
    <div class="text_box">
        <p class="text_box_name text-capitalize">
            <a href="{{ route('show_story', $slug) }}">
                {{ $name }}
            </a>
        </p>
        <p class="text_box_chapter">
            <span class="text_box_chapter_number">
                <a href="{{ route('show_chapter', [$slug, $chapterNumber]) }}">
                    Chương {{ $chapterNumber }}
                </a>
            </span>
            <span class="text_box_chapter_time">
                {{ $chapterTime }}
            </span>
        </p>
    </div>
</div>
