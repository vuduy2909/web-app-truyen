    <style>
        .sidebar .logo {
            height: 60px;
            position: relative;
            margin-top: 4px;
            padding: 0 24px;
        }

        .logo img {
            height: 100%;
            object-fit: cover;
        }
    </style>
    <div class="sidebar" data-color="azure" data-image="{{ asset('admin_asset/img/full-screen-image-3.jpg') }}">
        <!--

        Tip 1: you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple"
        Tip 2: you can also add an image using data-image tag

    -->

        <div class="logo" style="overflow: hidden;">
            <span class="sidebar-normal">
                <a href="{{ route('index') }}">
                    <img src="{{ asset('img/page/logo.png') }}" alt="logo">
                </a>
            </span>
        </div>

        <div class="sidebar-wrapper">

            <div class="user">
                <div class="info">
                    @auth
                        <div class="photo">
                            @if (isset(auth()->user()->avatar))
                                <img src="{{ auth()->user()->avatar_url }}">
                            @else
                                <img src="{{ asset('img/no_face.png') }}">
                            @endif
                        </div>

                        <a data-toggle="collapse" href="#collapseExample" class="collapsed">
                            <span>
                                {{ auth()->user()->name }}
                                <b class="caret"></b>
                            </span>
                            <span style="margin-top: 4px">
                                {{ auth()->user()->level->name }}
                            </span>
                        </a>

                        <div class="collapse" id="collapseExample">
                            <ul class="nav">
                                <li>
                                    <a href="#pablo">
                                        <span class="sidebar-mini">MP</span>
                                        <span class="sidebar-normal">My Profile</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="#pablo">
                                        <span class="sidebar-mini">EP</span>
                                        <span class="sidebar-normal">Edit Profile</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="#pablo">
                                        <span class="sidebar-mini">S</span>
                                        <span class="sidebar-normal">Settings</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endauth
                </div>
            </div>
            <ul class="nav">
                @auth
                    @if (auth()->user()->level_id === 2 || auth()->user()->level_id === 3)
                        <li>
                            <a href="{{ route('admin.index') }}">
                                <span class="sidebar-mini">
                                    <i class="pe-7s-graph"></i>
                                </span>
                                <p class="sidebar-normal">Thống kê</p>
                            </a>
                        </li>
                        @if (auth()->user()->level_id === 3)
                            <li>
                                <a data-toggle="collapse" href="#userHandle">
                                    <i class="pe-7s-users"></i>
                                    <p>Xử lý người dùng
                                        <b class="caret"></b>
                                    </p>
                                </a>
                                <div class="collapse" id="userHandle">
                                    <ul class="nav">
                                        <li>
                                            <a href="{{ route('admin.levels.index') }}">
                                                <span class="sidebar-mini">Cb</span>
                                                <span class="sidebar-normal">Cấp bậc</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.users.index') }}">
                                                <span class="sidebar-mini">Nd</span>
                                                <span class="sidebar-normal">Người dùng</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.users.black_list') }}">
                                                <span class="sidebar-mini">Sd</span>
                                                <span class="sidebar-normal">Sổ đen</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif
                        {{-- xử lý truyện --}}
                        <li>
                            <a data-toggle="collapse" href="#storiesHandle">
                                <i class="pe-7s-note2"></i>
                                <p>Xử lý truyện
                                    <b class="caret"></b>
                                </p>
                            </a>
                            <div class="collapse" id="storiesHandle">
                                <ul class="nav">
                                    <li>
                                        <a href="{{ route('admin.categories.index') }}">
                                            <span class="sidebar-mini">TL</span>
                                            <span class="sidebar-normal">Thể loại</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.stories.index') }}">
                                            <span class="sidebar-mini">Tr</span>
                                            <span class="sidebar-normal">Danh sách truyện</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.chapters.index') }}">
                                            <span class="sidebar-mini">Ch</span>
                                            <span class="sidebar-normal">Chương chờ duyệt</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif
                    {{--  truyện của tôi --}}
                    <li>
                        <a data-toggle="collapse" href="#myStories">
                            <i class="pe-7s-note2"></i>
                            <p>Truyện của tôi
                                <b class="caret"></b>
                            </p>
                        </a>
                        <div class="collapse" id="myStories">
                            <ul class="nav">
                                <li>
                                    <a href="{{ route('user.index') }}">
                                        <span class="sidebar-mini">TH</span>
                                        <span class="sidebar-normal">Tổng hợp truyện</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.categories.index') }}">
                                        <span class="sidebar-mini">TL</span>
                                        <span class="sidebar-normal">Thể loại</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.stories.index') }}">
                                        <span class="sidebar-mini">Tr</span>
                                        <span class="sidebar-normal">Danh sách truyện</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.stories.black_list') }}">
                                        <span class="sidebar-mini">TX</span>
                                        <span class="sidebar-normal">Truyện đã xóa</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.chapters.index') }}">
                                        <span class="sidebar-mini">Ch</span>
                                        <span class="sidebar-normal">Chương không được duyệt</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
