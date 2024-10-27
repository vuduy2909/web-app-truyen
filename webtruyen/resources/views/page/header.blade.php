@extends('layout.front_page.master')
@section('main')
    <div class="container">
        @push('css')
            <link rel="stylesheet" href="{{ asset('front_asset/css/style.css') }}">
            <link rel="stylesheet" href="{{ asset('front_asset/css/own/index.css') }}">
            <link rel="stylesheet" href="{{ asset('front_asset/css/own/owl.carousel.min.css') }}">
            <link rel="stylesheet" href="{{ asset('front_asset/css/own/owl.theme.default.min.css') }}">
        @endpush
            <!-- slide -->
            <div class="">
                <h3 class="mt-4 mb-0" style="color: #2980b9;">Truyện đề cử</h3>
            </div>
            <div class="owl-carousel owl-theme mt-2">
                <div class="item">
                    <a href="#">
                        <img src="https://static.8cache.com/cover/eJzLyTDW90-0zLAI9C9MTnROtPQI8cj0z8w2D_NPMzV1MU7N9IxKKXFzczZ38jFx9E11K3NODMwKyHb1TC03SgwLzYyoCMrNN_d3T3H1jnAtcQoOTim1LTcyNNXNMDYyAgCcgR35/tuong-thanh-qua-phu-khi-con-tre.jpg" alt="">
                    </a>
                    <div class="item_view">
                        <p class="item_view_name">
                            <a href="/HTML/Truyen/idol.html">
                                [Vong Tiện] Idol Tiện Kỳ Ảo Chi Lữamcjjdnaisdjoiaefs,nfeiia ueb
                            </a>
                        </p>
                        <p class="item_view_chapter">
                        <span class="item_view_chapter_number">
                            <a href="#">
                                Chương 1
                            </a>
                        </span>
                            <span class="item_view_chapter_time">
                            3 giờ trước
                        </span>
                        </p>
                    </div>
                </div>
            </div>
        <!-- {{-- ...grid... --}} -->
        <div class="row">
            <div class="mb-3 col-12 col-md-8">
                <div class="mt-4 mb-1 d-flex justify-content-between">
                    <h3 class="fit-width" style="color: #2980b9;"> Truyện mới</h3>
                    <a href="{{ route('advanced_search') }}" class="btn btn-outline-warning">
                        <i class="fa-solid fa-filter text-success"></i>
                    </a>
                </div>
                <div class="row">
                    @for($i = 1; $i <= 10; $i++)
                        <x-story/>
                    @endfor
                    <div class="col-lg-3 col-sm-4 col-6">
                        <div class="item_box">
                            <a href="/HTML/Truyen/idol.html">
                                <img src="https://static.8cache.com/cover/eJzLyTDW90-0zLAI9C9MTnROtPQI8cj0z8w2D_NPMzV1MU7N9IxKKXFzczZ38jFx9E11K3NODMwKyHb1TC03SgwLzYyoCMrNN_d3T3H1jnAtcQoOTim1LTcyNNXNMDYyAgCcgR35/tuong-thanh-qua-phu-khi-con-tre.jpg">
                            </a>
                            <div class="box_view">
                                <i class="fa-regular fa-eye"></i>1000 lượt xem
                            </div>
                            <div class="text_box">
                                <p class="text_box_name">
                                    <a href="/HTML/Truyen/idol.html">
                                        [Vong Tiện] Idol Tiện Kỳ Ảo Chi Lữamcjjdnaisdjoiaefs,nfeiia ueb
                                    </a>
                                </p>
                                <p class="text_box_chapter">
                                    <span class="text_box_chapter_number">
                                        <a href="#">
                                            Chương 1
                                        </a>
                                    </span>
                                    <span class="text_box_chapter_time">
                                        3 giờ trước
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-6">
                        <div class="item_box">
                            <a href="/HTML/Truyen/idol.html">
                                <img src="https://static.8cache.com/cover/eJzLyTDW90-0zLAI9C9MTnROtPQI8cj0z8w2D_NPMzV1MU7N9IxKKXFzczZ38jFx9E11K3NODMwKyHb1TC03SgwLzYyoCMrNN_d3T3H1jnAtcQoOTim1LTcyNNXNMDYyAgCcgR35/tuong-thanh-qua-phu-khi-con-tre.jpg">
                            </a>
                            <div class="box_view">
                                <i class="fa-regular fa-eye"></i>1000 lượt xem
                            </div>
                            <div class="text_box">
                                <p class="text_box_name">
                                    <a href="/HTML/Truyen/idol.html">
                                        [Vong Tiện] Idol Tiện Kỳ Ảo Chi Lữamcjjdnaisdjoiaefs,nfeiia ueb
                                    </a>
                                </p>
                                <p class="text_box_chapter">
                                    <span class="text_box_chapter_number">
                                        <a href="#">
                                            Chương 1
                                        </a>
                                    </span>
                                    <span class="text_box_chapter_time">
                                        3 giờ trước
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="row mt-4">
                    <!-- -----------history---------- -->
                    <div class="col-12 mb-3">
                        <div class="history">
                            <div class="history_title">
                                Lịch sử đọc truyện
                            </div>
                            <div class="history_list">
                                <div class="list_li">
                                    <div class="history_img">
                                        <a href="">
                                            <img src="https://img.8cache.com/hot-9.jpg" alt="">
                                        </a>
                                    </div>
                                    <div class="history_info">
                                        <p class="history_info_name">
                                            <a href="/HTML/Truyen/idol.html">
                                                [Vong Tiện] Idol Tiện Kỳ Ảo Chi Lữ sy gdfgdfhssgfbgfjhf sêgsdhfg
                                            </a>
                                        </p>
                                        <div class="history_info_chapter">
                                            <span class="history_info_chapter_number">
                                                <a href="#">
                                                    Chương 1
                                                </a>
                                            </span>
                                            <div class="history_info_chapter_views">
                                                <i class="fa-regular fa-eye"></i>1000
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="list_li">
                                    <div class="history_img">
                                        <a href="">
                                            <img src="https://img.8cache.com/hot-9.jpg" alt="">
                                        </a>
                                    </div>
                                    <div class="history_info">
                                        <p class="history_info_name">
                                            <a href="/HTML/Truyen/idol.html">
                                                [Vong Tiện] Idol Tiện Kỳ Ảo Chi Lữ sy gdfgdfhssgfbgfjhf sêgsdhfg
                                            </a>
                                        </p>
                                        <div class="history_info_chapter">
                                            <span class="history_info_chapter_number">
                                                <a href="#">
                                                    Chương 1
                                                </a>
                                            </span>
                                            <div class="history_info_chapter_views">
                                                <i class="fa-regular fa-eye"></i>1000
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="list_li">
                                    <div class="history_img">
                                        <a href="">
                                            <img src="https://img.8cache.com/hot-9.jpg" alt="">
                                        </a>
                                    </div>
                                    <div class="history_info">
                                        <p class="history_info_name">
                                            <a href="/HTML/Truyen/idol.html">
                                                [Vong Tiện] Idol Tiện Kỳ Ảo Chi Lữ sy gdfgdfhssgfbgfjhf sêgsdhfg
                                            </a>
                                        </p>
                                        <div class="history_info_chapter">
                                            <span class="history_info_chapter_number">
                                                <a href="#">
                                                    Chương 1
                                                </a>
                                            </span>
                                            <div class="history_info_chapter_views">
                                                <i class="fa-regular fa-eye"></i>1000
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- --------top truyen---------- -->
                    <div class="col-12 mb-3">
                        <div class="rank_top">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active"
                                       id="top-star-tab"
                                       data-toggle="tab"
                                       href="#top-star"
                                       role="tab"
                                       aria-controls="top-star"
                                       aria-selected="true">Top đánh giá</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link"
                                       id="top-month-tab"
                                       data-toggle="tab"
                                       href="#top-month"
                                       role="tab"
                                       aria-controls="top-month"
                                       aria-selected="false">Top tháng</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link"
                                       id="top-week-tab"
                                       data-toggle="tab"
                                       href="#top-week"
                                       role="tab"
                                       aria-controls="top-week"
                                       aria-selected="false">Top tuần</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link"
                                       id="top-day-tab"
                                       data-toggle="tab"
                                       href="#top-day"
                                       role="tab"
                                       aria-controls="top-day"
                                       aria-selected="false">Top ngày</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="top-star" role="tabpanel" aria-labelledby="top-star-tab">
                                    <div class="list_li">
                                        <div class="rank_top_number">
                                            <span class="pos1">01</span>
                                        </div>
                                        <div class="rank_top_img">
                                            <a href="">
                                                <img src="https://img.8cache.com/hot-9.jpg" alt="">
                                            </a>
                                        </div>
                                        <div class="rank_top_info">
                                            <p class="rank_top_info_name">
                                                <a href="/HTML/Truyen/idol.html">
                                                    [Vong Tiện] Idol Tiện Kỳ Ảo Chi Lữ sy gdfgdfhssgfbgfjhf sêgsdhfg
                                                </a>
                                            </p>
                                            <div class="rank_top_info_chapter">
                                                <span class="rank_top_info_chapter_number">
                                                    <a href="#">
                                                        Chương 1
                                                    </a>
                                                </span>
                                                <div class="rank_top_info_chapter_views">
                                                    <i class="fa-regular fa-eye"></i>1000
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list_li">
                                        <div class="rank_top_number">
                                            <span class="pos2">02</span>
                                        </div>
                                        <div class="rank_top_img">
                                            <a href="">
                                                <img src="https://img.8cache.com/hot-9.jpg" alt="">
                                            </a>
                                        </div>
                                        <div class="rank_top_info">
                                            <p class="rank_top_info_name">
                                                <a href="/HTML/Truyen/idol.html">
                                                    [Vong Tiện] Idol Tiện Kỳ Ảo Chi Lữ sy gdfgdfhssgfbgfjhf sêgsdhfg
                                                </a>
                                            </p>
                                            <div class="rank_top_info_chapter">
                                                <span class="rank_top_info_chapter_number">
                                                    <a href="#">
                                                        Chương 1
                                                    </a>
                                                </span>
                                                <div class="rank_top_info_chapter_views">
                                                    <i class="fa-regular fa-eye"></i>1000
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list_li">
                                        <div class="rank_top_number">
                                            <span class="pos3">03</span>
                                        </div>
                                        <div class="rank_top_img">
                                            <a href="">
                                                <img src="https://img.8cache.com/hot-9.jpg" alt="">
                                            </a>
                                        </div>
                                        <div class="rank_top_info">
                                            <p class="rank_top_info_name">
                                                <a href="/HTML/Truyen/idol.html">
                                                    [Vong Tiện] Idol Tiện Kỳ Ảo Chi Lữ sy gdfgdfhssgfbgfjhf sêgsdhfg
                                                </a>
                                            </p>
                                            <div class="rank_top_info_chapter">
                                                <span class="rank_top_info_chapter_number">
                                                    <a href="#">
                                                        Chương 1
                                                    </a>
                                                </span>
                                                <div class="rank_top_info_chapter_views">
                                                    <i class="fa-regular fa-eye"></i>1000
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list_li">
                                        <div class="rank_top_number">
                                            <span class="pos_normal">04</span>
                                        </div>
                                        <div class="rank_top_img">
                                            <a href="">
                                                <img src="https://img.8cache.com/hot-9.jpg" alt="">
                                            </a>
                                        </div>
                                        <div class="rank_top_info">
                                            <p class="rank_top_info_name">
                                                <a href="/HTML/Truyen/idol.html">
                                                    [Vong Tiện] Idol Tiện Kỳ Ảo Chi Lữ sy gdfgdfhssgfbgfjhf sêgsdhfg
                                                </a>
                                            </p>
                                            <div class="rank_top_info_chapter">
                                                <span class="rank_top_info_chapter_number">
                                                    <a href="#">
                                                        Chương 1
                                                    </a>
                                                </span>
                                                <div class="rank_top_info_chapter_views">
                                                    <i class="fa-regular fa-eye"></i>1000
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="top-month" role="tabpanel" aria-labelledby="top-month-tab">
                                    <div class="list_li">
                                        <div class="rank_top_number">
                                            <span class="pos1">01</span>
                                        </div>
                                        <div class="rank_top_img">
                                            <a href="">
                                                <img src="https://img.8cache.com/hot-9.jpg" alt="">
                                            </a>
                                        </div>
                                        <div class="rank_top_info">
                                            <p class="rank_top_info_name">
                                                <a href="/HTML/Truyen/idol.html">
                                                    [Vong Tiện] Idol Tiện Kỳ Ảo Chi Lữ sy gdfgdfhssgfbgfjhf sêgsdhfg
                                                </a>
                                            </p>
                                            <div class="rank_top_info_chapter">
                                                <span class="rank_top_info_chapter_number">
                                                    <a href="#">
                                                        Chương 1
                                                    </a>
                                                </span>
                                                <div class="rank_top_info_chapter_views">
                                                    <i class="fa-regular fa-eye"></i>1000
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list_li">
                                        <div class="rank_top_number">
                                            <span class="pos2">02</span>
                                        </div>
                                        <div class="rank_top_img">
                                            <a href="">
                                                <img src="https://img.8cache.com/hot-9.jpg" alt="">
                                            </a>
                                        </div>
                                        <div class="rank_top_info">
                                            <p class="rank_top_info_name">
                                                <a href="/HTML/Truyen/idol.html">
                                                    [Vong Tiện] Idol Tiện Kỳ Ảo Chi Lữ sy gdfgdfhssgfbgfjhf sêgsdhfg
                                                </a>
                                            </p>
                                            <div class="rank_top_info_chapter">
                                                <span class="rank_top_info_chapter_number">
                                                    <a href="#">
                                                        Chương 1
                                                    </a>
                                                </span>
                                                <div class="rank_top_info_chapter_views">
                                                    <i class="fa-regular fa-eye"></i>1000
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list_li">
                                        <div class="rank_top_number">
                                            <span class="pos3">03</span>
                                        </div>
                                        <div class="rank_top_img">
                                            <a href="">
                                                <img src="https://img.8cache.com/hot-9.jpg" alt="">
                                            </a>
                                        </div>
                                        <div class="rank_top_info">
                                            <p class="rank_top_info_name">
                                                <a href="/HTML/Truyen/idol.html">
                                                    [Vong Tiện] Idol Tiện Kỳ Ảo Chi Lữ sy gdfgdfhssgfbgfjhf sêgsdhfg
                                                </a>
                                            </p>
                                            <div class="rank_top_info_chapter">
                                                <span class="rank_top_info_chapter_number">
                                                    <a href="#">
                                                        Chương 1
                                                    </a>
                                                </span>
                                                <div class="rank_top_info_chapter_views">
                                                    <i class="fa-regular fa-eye"></i>1000
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list_li">
                                        <div class="rank_top_number">
                                            <span>04</span>
                                        </div>
                                        <div class="rank_top_img">
                                            <a href="">
                                                <img src="https://img.8cache.com/hot-9.jpg" alt="">
                                            </a>
                                        </div>
                                        <div class="rank_top_info">
                                            <p class="rank_top_info_name">
                                                <a href="/HTML/Truyen/idol.html">
                                                    [Vong Tiện] Idol Tiện Kỳ Ảo Chi Lữ sy gdfgdfhssgfbgfjhf sêgsdhfg
                                                </a>
                                            </p>
                                            <div class="rank_top_info_chapter">
                                                <span class="rank_top_info_chapter_number">
                                                    <a href="#">
                                                        Chương 1
                                                    </a>
                                                </span>
                                                <div class="rank_top_info_chapter_views">
                                                    <i class="fa-regular fa-eye"></i>1000
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="top-week" role="tabpanel" aria-labelledby="top-week-tab">
                                    <div class="list_li">
                                        <div class="rank_top_number">
                                            <span class="pos1">01</span>
                                        </div>
                                        <div class="rank_top_img">
                                            <a href="">
                                                <img src="https://img.8cache.com/hot-9.jpg" alt="">
                                            </a>
                                        </div>
                                        <div class="rank_top_info">
                                            <p class="rank_top_info_name">
                                                <a href="/HTML/Truyen/idol.html">
                                                    [Vong Tiện] Idol Tiện Kỳ Ảo Chi Lữ sy gdfgdfhssgfbgfjhf sêgsdhfg
                                                </a>
                                            </p>
                                            <div class="rank_top_info_chapter">
                                                <span class="rank_top_info_chapter_number">
                                                    <a href="#">
                                                        Chương 1
                                                    </a>
                                                </span>
                                                <div class="rank_top_info_chapter_views">
                                                    <i class="fa-regular fa-eye"></i>1000
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list_li">
                                        <div class="rank_top_number">
                                            <span class="pos2">02</span>
                                        </div>
                                        <div class="rank_top_img">
                                            <a href="">
                                                <img src="https://img.8cache.com/hot-9.jpg" alt="">
                                            </a>
                                        </div>
                                        <div class="rank_top_info">
                                            <p class="rank_top_info_name">
                                                <a href="/HTML/Truyen/idol.html">
                                                    [Vong Tiện] Idol Tiện Kỳ Ảo Chi Lữ sy gdfgdfhssgfbgfjhf sêgsdhfg
                                                </a>
                                            </p>
                                            <div class="rank_top_info_chapter">
                                                <span class="rank_top_info_chapter_number">
                                                    <a href="#">
                                                        Chương 1
                                                    </a>
                                                </span>
                                                <div class="rank_top_info_chapter_views">
                                                    <i class="fa-regular fa-eye"></i>1000
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list_li">
                                        <div class="rank_top_number">
                                            <span class="pos3">03</span>
                                        </div>
                                        <div class="rank_top_img">
                                            <a href="">
                                                <img src="https://img.8cache.com/hot-9.jpg" alt="">
                                            </a>
                                        </div>
                                        <div class="rank_top_info">
                                            <p class="rank_top_info_name">
                                                <a href="/HTML/Truyen/idol.html">
                                                    [Vong Tiện] Idol Tiện Kỳ Ảo Chi Lữ sy gdfgdfhssgfbgfjhf sêgsdhfg
                                                </a>
                                            </p>
                                            <div class="rank_top_info_chapter">
                                                <span class="rank_top_info_chapter_number">
                                                    <a href="#">
                                                        Chương 1
                                                    </a>
                                                </span>
                                                <div class="rank_top_info_chapter_views">
                                                    <i class="fa-regular fa-eye"></i>1000
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list_li">
                                        <div class="rank_top_number">
                                            <span>04</span>
                                        </div>
                                        <div class="rank_top_img">
                                            <a href="">
                                                <img src="https://img.8cache.com/hot-9.jpg" alt="">
                                            </a>
                                        </div>
                                        <div class="rank_top_info">
                                            <p class="rank_top_info_name">
                                                <a href="/HTML/Truyen/idol.html">
                                                    [Vong Tiện] Idol Tiện Kỳ Ảo Chi Lữ sy gdfgdfhssgfbgfjhf sêgsdhfg
                                                </a>
                                            </p>
                                            <div class="rank_top_info_chapter">
                                                <span class="rank_top_info_chapter_number">
                                                    <a href="#">
                                                        Chương 1
                                                    </a>
                                                </span>
                                                <div class="rank_top_info_chapter_views">
                                                    <i class="fa-regular fa-eye"></i>1000
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="top-day" role="tabpanel" aria-labelledby="top-day-tab">
                                    <div class="list_li">
                                        <div class="rank_top_number">
                                            <span class="pos1">01</span>
                                        </div>
                                        <div class="rank_top_img">
                                            <a href="">
                                                <img src="https://img.8cache.com/hot-9.jpg" alt="">
                                            </a>
                                        </div>
                                        <div class="rank_top_info">
                                            <p class="rank_top_info_name">
                                                <a href="/HTML/Truyen/idol.html">
                                                    [Vong Tiện] Idol Tiện Kỳ Ảo Chi Lữ sy gdfgdfhssgfbgfjhf sêgsdhfg
                                                </a>
                                            </p>
                                            <div class="rank_top_info_chapter">
                                                <span class="rank_top_info_chapter_number">
                                                    <a href="#">
                                                        Chương 1
                                                    </a>
                                                </span>
                                                <div class="rank_top_info_chapter_views">
                                                    <i class="fa-regular fa-eye"></i>1000
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list_li">
                                        <div class="rank_top_number">
                                            <span class="pos2">02</span>
                                        </div>
                                        <div class="rank_top_img">
                                            <a href="">
                                                <img src="https://img.8cache.com/hot-9.jpg" alt="">
                                            </a>
                                        </div>
                                        <div class="rank_top_info">
                                            <p class="rank_top_info_name">
                                                <a href="/HTML/Truyen/idol.html">
                                                    [Vong Tiện] Idol Tiện Kỳ Ảo Chi Lữ sy gdfgdfhssgfbgfjhf sêgsdhfg
                                                </a>
                                            </p>
                                            <div class="rank_top_info_chapter">
                                                <span class="rank_top_info_chapter_number">
                                                    <a href="#">
                                                        Chương 1
                                                    </a>
                                                </span>
                                                <div class="rank_top_info_chapter_views">
                                                    <i class="fa-regular fa-eye"></i>1000
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list_li">
                                        <div class="rank_top_number">
                                            <span class="pos3">03</span>
                                        </div>
                                        <div class="rank_top_img">
                                            <a href="">
                                                <img src="https://img.8cache.com/hot-9.jpg" alt="">
                                            </a>
                                        </div>
                                        <div class="rank_top_info">
                                            <p class="rank_top_info_name">
                                                <a href="/HTML/Truyen/idol.html">
                                                    [Vong Tiện] Idol Tiện Kỳ Ảo Chi Lữ sy gdfgdfhssgfbgfjhf sêgsdhfg
                                                </a>
                                            </p>
                                            <div class="rank_top_info_chapter">
                                                <span class="rank_top_info_chapter_number">
                                                    <a href="#">
                                                        Chương 1
                                                    </a>
                                                </span>
                                                <div class="rank_top_info_chapter_views">
                                                    <i class="fa-regular fa-eye"></i>1000
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list_li">
                                        <div class="rank_top_number">
                                            <span>04</span>
                                        </div>
                                        <div class="rank_top_img">
                                            <a href="">
                                                <img src="https://img.8cache.com/hot-9.jpg" alt="">
                                            </a>
                                        </div>
                                        <div class="rank_top_info">
                                            <p class="rank_top_info_name">
                                                <a href="/HTML/Truyen/idol.html">
                                                    [Vong Tiện] Idol Tiện Kỳ Ảo Chi Lữ sy gdfgdfhssgfbgfjhf sêgsdhfg
                                                </a>
                                            </p>
                                            <div class="rank_top_info_chapter">
                                                <span class="rank_top_info_chapter_number">
                                                    <a href="#">
                                                        Chương 1
                                                    </a>
                                                </span>
                                                <div class="rank_top_info_chapter_views">
                                                    <i class="fa-regular fa-eye"></i>1000
                                                </div>
                                            </div>
                                        </div>
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
        <script src="{{ asset('front_asset/js/own/owl.carousel.js') }}"></script>
        <script>
            $('.owl-carousel').owlCarousel({
                loop: true,
                margin: 10,
                nav: true,
                autoplay: true,
                autoplayTimeout: 1500,
                autoplayHoverPause: true,

                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 3
                    },
                    1000: {
                        items: 5
                    }
                }
            })
        </script>
    @endpush
@endsection
