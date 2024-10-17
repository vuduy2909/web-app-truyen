@extends('layout.front_page.master')
@section('main')
    @push('css')
        <style>
            main {
                /*background-color: #e9edf0;*/
                /*background: #b5b5b5;*/
                background: var(--color-main);
            }
            body.dark main {
                background: var(--body-dark);
            }
            body.dark main {
                 color: rgba(255,255,255,0.6);
            }
            .story_name h2 {
                font-size: 1.5rem;
                margin: 24px 0 5px;
                line-height: 35px;
                text-transform: uppercase;
                font-family: "Roboto Condensed", Tahoma, sans-serif;
                text-align: center;
            }
            .chapter_name {
                font-size: 1rem;
                margin: 0 0 5px;
                line-height: 35px;
                text-align: center;
            }

            hr {
                margin: auto;
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

            .content p {
                text-align: justify;
                font-size: 1rem;
                line-height: 100%;
            }
            body.dark .comments_box {
                background-color: #e1e1e1;
            }
            .lentop {
                display:none;
                bottom: 10%;
                right: 10px;
                cursor: pointer;
                position: fixed;
                z-index: 1000;
            }
            .lentop div {
                background:#8C52FF;
                border:2px solid #fff;
                border-radius:45px;
                padding:11px 12.5px;
                box-shadow: 1px 3px 5px 0px rgba(0, 0, 0, 0.3)
            }
            .lentop img {
                width:16px;
                height:16px;
            }
        </style>
    @endpush
{{--    <div class="row">--}}
{{--        <div class="col-md-12">--}}
{{--            {{ Breadcrumbs::render('admin.chapters.show', $story, $chapter) }}--}}
{{--        </div>--}}
{{--    </div>--}}
    <div class="row">
        <div class="col-md-12">
            <div class="story_name">
                <h2>{{ $story->name }}</h2>
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
                <a href="{{ route("show_chapter", [$story->slug, $pre]) }}"
                   class="btn btn-primary btn-sm @if ($chapter->number === $first) disabled @endif">
                    Chương trước</a>
                <div class="dropdown">
                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">Chọn
                        chương
                        <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        @foreach ($chapterList as $chapterItem)
                            <a class="dropdown-item @if ($chapterItem === $chapter->number) active @endif"
                               href="{{ route("show_chapter", [$story->slug, $chapterItem]) }}">
                                Chương {{ $chapterItem }}
                            </a>
                        @endforeach
                    </ul>
                </div>
                <a href="{{ route("show_chapter", [$story->slug, $next]) }}"
                   class="btn btn-primary btn-sm @if ($chapter->number === $last) disabled @endif">Chương
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
                <a href="{{ route("show_chapter", [$story->slug, $pre]) }}"
                   class="btn btn-primary btn-sm @if ($chapter->number === $first) disabled @endif">
                    Chương trước</a>
                <div class="dropup">
                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">Chọn
                        chương
                        <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        @foreach ($chapterList as $chapterItem)
                            <a class="dropdown-item @if ($chapterItem === $chapter->number) active @endif"
                               href="{{ route("show_chapter", [$story->slug, $chapterItem]) }}">
                                Chương {{ $chapterItem }}
                            </a>
                        @endforeach
                    </ul>
                </div>
                <a href="{{ route("show_chapter", [$story->slug, $next]) }}"
                   class="btn btn-primary btn-sm @if ($chapter->number === $last) disabled @endif">Chương
                    sau
                </a>
            </div>
        </div>
    </div>
    <div class='lentop'>
        <div>
            <img src='https://1.bp.blogspot.com/-k6sikOdzFXQ/VwqCKDosmEI/AAAAAAAAKxE/nLxLhkTIO6o3iE5ZWmtxo2bf4QHdzPQNQ/s1600/top.png' />
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 comments_box">
            <div class="fb-comments" data-href="{{ route('show_story', $story->slug) }}"
                 data-width="100%" data-numposts="10"></div>
        </div>
    </div>
    @push('js')
        <script src="https://code.jquery.com/jquery-latest.js"></script>
        <script>
            // ----------tao nut len dau trang-----------

            $(function() {
                $(window).scroll(function() {
                    if ($(this).scrollTop() > 100) $(".lentop").fadeIn();
                    else $(".lentop").fadeOut();
                });
                $(".lentop").click(function() {
                    $("body,html").animate({ scrollTop: 0 }, "slow");
                });
            });
        </script>
    @endpush
@endsection
