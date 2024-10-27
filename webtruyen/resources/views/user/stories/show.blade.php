@extends('layout.admin_and_user_page.master')
@section('main')
    @push('css')
        <link rel="stylesheet" href="{{ asset('user_asset/css/story_view.css') }}">
    @endpush
    <div class="row">
        <div class="col-md-7">
            {{ Breadcrumbs::render("user.$table.show", $story) }}
        </div>
        <div class="col-md-5">
            <div class="stories_box">
                <div class="card">
                    <form action="{{ route("user.$table.find") }}" method="get" id="stories_box_form"
                          class="form-inline">
                        <div class="content">
                            <div class="form-group">
                                <label for="story_id" class="control-label">Chọn truyện</label>
                                <select name="story_id" id="story_id" class="form-control">
                                    @foreach ($stories as $item)
                                        <option value="{{ $item->id }}"
                                                @if ($item->id === $story->id) selected @endif>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="story_info">
        <div class="story_title">
            <h3>Thông tin truyện</h3>
            <div class="button_box" style="display: flex; justify-content: space-between;">
                <form
                    action='{{ route("user.$table.upload", $story->id) }}'
                    method="post">
                    @csrf
                    <button style="background: transparent; border: none;">
                        <div class="checkbox" style="margin-top: 8px;">
                            <input type="checkbox" id="pin-{{ $story->id }}"
                                   @if($story->pin > \App\Enums\StoryPinEnum::EDITING)
                                       checked
                                @endif
                            >
                            <label data-original-title="Đăng lên" rel="tooltip"
                                   for="pin-{{ $story->id }}"></label>
                        </div>
                    </button>
                </form>
                <a rel="tooltip" class="btn btn-simple btn-warning btn-icon edit"
                   href="{{ route("user.$table.edit", $story->id) }}" data-original-title="Thay đổi thông tin truyện">
                    <i class="fa fa-edit"></i>
                </a>
            </div>
        </div>
        <div class="story_name">
            <h2>{{ $story->name }}</h2>
        </div>
        <div class="story_box">
            <div class="story_box_left">
                <div class="story_box_left_top">
                    <a href="{{ $story->image_url }}" target="_blank">
                        <div class="story_box_left_top_image">
                            <img src="{{ $story->image_url }}" alt="{{ $story->name }}">
                            <div class="book-shadow"></div>
                        </div>
                    </a>
                </div>
                <div class="story_box_left_bottom">
                    <div class="story_box_left_bottom_item">
                        <strong>Phân loại:</strong>
                        <span>{{ $story->level_name }}</span>
                    </div>
                    <div class="story_box_left_bottom_item">
                        <strong>Tác giả:</strong>
                        <span>{{ $story->author->name }}</span>
                    </div>
                    @isset($story->author_2)
                        <div class="story_box_left_bottom_item">
                            <strong>Người sửa:</strong>
                            <span>{{ $story->author_2->name }}</span>
                        </div>
                    @endisset
                    <div class="story_box_left_bottom_item">
                        <strong>Thể loại:</strong>
                        <span>{!! $story->categories_link !!}</span>
                    </div>
                    <div class="story_box_left_bottom_item">
                        <strong>Tình trạng:</strong>
                        <span>{{ $story->status_name }}</span>
                    </div>
                    <div class="story_box_left_bottom_item">
                        <strong>Trạng thái:</strong>
                        <span>{{ $story->pin_name }}</span>
                    </div>
                    <div class="story_box_left_bottom_item">
                        <strong>Số chương:</strong>
                        <span>{{ $story->chapter_count ?? 0 }}</span>
                    </div>
                </div>
            </div>
            <div class="story_box_right">
                <div class="story_box_right_rate">

                </div>
                <div class="story_box_right_desc">
                    {!! $story->descriptions !!}
                </div>
            </div>
        </div>
    </div>
    <div class="chapter_box">
        <div class="story_title">
            <h3>Danh sách chương</h3>
            <form class="form-inline" id="form_pin-filter">
                <div class="form-group">
                    <label class="control-label"> Lọc: </label>
                    <select name="chapter_pin_filter" class="form-control" id="select_pin-filter">
                        <option value="All">Tất cả</option>
                        @foreach ($ChapterPin as $value => $name)
                            <option value="{{ $value }}"
                            @if($chapterPinFilter === (string) $value) selected @endif
                            >{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
            <div class="button" style="display: flex; justify-content: space-between;">
                <form
                    action='{{ route("user.$table.chapters.upload_all", ['slug' => $story->slug]) }}'
                    method="post" >
                    @csrf
                    <button class="btn btn-simple btn-info btn-icon edit" rel="tooltip" data-original-title="Đăng tất cả">
                        <i class="fa fa-check"></i>
                    </button>
                </form>
                <a rel="tooltip" data-original-title="Thêm chương truyện" class="btn btn-simple btn-success btn-icon edit"
                   href="{{ route("user.$table.chapters.create", $story->slug) }}">
                    <i class="fa fa-plus-circle"></i>
                </a>
            </div>
        </div>
        <div class="chapter_list">
            <table style="width: 100%">
            @foreach ($chapters as $chapter)
                <tr class="chapter_box_item">
                    <td><a style="display: flex; align-items: center;"
                       href="{{ route("user.$table.chapters.show", ['slug' => $story->slug, 'number' => $chapter->number]) }}">
                        <span class="chapter_box_item_text">
                            <span>Chương </span> {{ $chapter->number }}: {{ $chapter->name }}
                        </span>
                    </a></td>
                    <td><span>{{ $chapter->pin_name }} </span></td>
                    <td class="button_group" style="justify-content: flex-end">
                        <form
                            action='{{ route("user.$table.chapters.upload", ['slug' => $story->slug, 'id' => $chapter->id]) }}'
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
                        <form action="{{ route("user.$table.chapters.destroy", ['slug' => $story->slug, 'number' => $chapter->number]) }}"
                              method="post">
                            @csrf
                            @method('delete')
                            <button rel="tooltip" data-original-title="Xóa"
                                    class="btn btn-simple btn-danger btn-icon table-action">
                                <i class="fa fa-remove"></i>
                            </button>
                        </form>

                    </td>
                </tr>
            @endforeach
            </table>
        </div>
        <div style="display: flex; justify-content: center;">
            {{ $chapters->links() }}
        </div>
    </div>
    @push('js')
        <script>
            const stories_box_form = document.querySelector('#stories_box_form');
            const story_id = document.getElementById('story_id');
            story_id.onchange = () => {
                stories_box_form.submit();
            }
        </script>
        <script>
            const formPinFilter = document.querySelector('#form_pin-filter');
            const selectPinFilter = document.getElementById('select_pin-filter');
            selectPinFilter.onchange = () => {
                formPinFilter.submit();
            }
        </script>
    @endpush
@endsection
