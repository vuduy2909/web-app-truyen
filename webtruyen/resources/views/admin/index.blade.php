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

            .text_box_chapter_time {
                float: right;
                font-style: italic;
                font-size: 14px;
                color: rgb(180, 178, 178);
            }

        </style>
    @endpush
    <div class="row">
        <div class="col-md-12">
            {{ Breadcrumbs::render('admin.index') }}
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="header">
                <h4 class="title">Thống kê người dùng</h4>
            </div>
            <div class="content">
                <div id="chartUser" class="ct-chart ">
                </div>
            </div>
            <div class="footer">
                <div class="legend">
                    Tổng: {{ $allUsers }}
                    <i class="fa fa-circle text-info" style="margin-left: 24px;"></i> User: {{ $userCount }}
                    <i class="fa fa-circle text-warning" style="margin-left: 16px;"></i> Censor: {{ $censorCount }}
                    <i class="fa fa-circle text-danger" style="margin-left: 16px;"></i> Admin: {{ $adminCount }}
                    <i class="fa fa-circle" style="margin-left: 16px; color: #9368E9;"></i> Black user: {{ $blackListCount }}
                </div>
                <hr>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="header">
                <h4 class="title">Thống kê truyện</h4>
            </div>
            <div class="content">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="title">Truyện được nhiều lượt xem nhất</h5>
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-xs-6">
                                <div class="item_box">
                                    <a href="{{ route('admin.stories.view', $maxViewStory->id) }}">
                                        <div class="box_image">
                                            <img
                                                src="{{ $maxViewStory->image_url }}" alt="">
                                            <div class="box_view" style="display: flex; justify-content: space-around;">
                                                <span><i class="fa fa-eye"></i> {{ $maxViewStory->view_count }}</span>
                                                <span><i class="fa fa-star-o"></i> {{ round($maxViewStory->star_avg_total, 1) }}</span>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="text_box">
                                        <p class="text_box_name text-capitalize">
                                            <a href="{{ route('admin.stories.view', $maxViewStory->id) }}">
                                                {{ $maxViewStory->name }}
                                            </a>
                                        </p>
                                        <p class="text_box_chapter">
                                            <span class="text_box_chapter_number">
                                                <a href="{{ route('admin.stories.chapters.show', [$maxViewStory->id, $maxViewStory->chapter_new_number]) }}">
                                                   Chương {{ $maxViewStory->chapter_new_number }}
                                                </a>
                                            </span>
                                            <span class="text_box_chapter_time">
                                                @php
                                                \Carbon\Carbon::setLocale('vi');
                                                @endphp
                                                {{ \Carbon\Carbon::make($maxViewStory->chapter_new_time)->diffForHumans() }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5 class="title">Truyện được ít lượt xem nhất</h5>
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-xs-6">
                                <div class="item_box">
                                    <a href="{{ route('admin.stories.view', $minViewStory->id) }}">
                                        <div class="box_image">
                                            <img
                                                src="{{ $minViewStory->image_url }}" alt="">
                                            <div class="box_view" style="display: flex; justify-content: space-around;">
                                                <span><i class="fa fa-eye"></i> {{ $minViewStory->view_count }}</span>
                                                <span><i class="fa fa-star-o"></i> {{ round($minViewStory->star_avg_total, 1) }}</span>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="text_box">
                                        <p class="text_box_name text-capitalize">
                                            <a href="{{ route('admin.stories.view', $minViewStory->id) }}">
                                                {{ $minViewStory->name }}
                                            </a>
                                        </p>
                                        <p class="text_box_chapter">
                                            <span class="text_box_chapter_number">
                                                <a href="{{ route('admin.stories.chapters.show', [$minViewStory->id, $minViewStory->chapter_new_number]) }}">
                                                   Chương {{ $minViewStory->chapter_new_number }}
                                                </a>
                                            </span>
                                            <span class="text_box_chapter_time">
                                                @php
                                                    \Carbon\Carbon::setLocale('vi');
                                                @endphp
                                                {{ \Carbon\Carbon::make($minViewStory->chapter_new_time)->diffForHumans() }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            let dataPreferences = {
                series: [
                    [25, 30, 20, 25]
                ]
            };
            let optionsPreferences = {
                donut: true,
                donutWidth: 40,
                startAngle: 0,
                height: "350px",
                total: 100,
                showLabel: false,
                axisX: {
                    showGrid: false
                }
            };

            Chartist.Pie('#chartUser', dataPreferences, optionsPreferences);

            Chartist.Pie('#chartUser', {
                labels: [{{ $userPercentage }} + '%', {{ $censorPercentage }} + '%', {{ $adminPercentage }} + '%', {{ $blackListPercentage }} + '%'],
                series: [{{ $userPercentage }}, {{ $censorPercentage }}, {{ $adminPercentage }}, {{ $blackListPercentage }}]
            });

        </script>
    @endpush
@endsection
