@extends('layout.admin_and_user_page.master')
@section('main')
    @push('css')
        <style>
            .image_box {
                position: relative;
                width: 100%;
                display: flex;
                justify-content: center;
                height: 313px;
            }

            .image_box .photo_img {
                font-size: 15em;
            }

            .image_box .photo_img::before {
                position: absolute;
                left: 50%;
                transform: translateX(-50%)
            }

            .autobox {
                background-color: #fff;
                width: 100%;
                overflow-y: auto;
                z-index: 5;
                border-bottom-left-radius: 5px;
                border-bottom-right-radius: 5px;
            }

            .autobox li {
                padding: 6px 0 6px 36px;
                cursor: pointer;
                display: none;
            }

            .autobox li:hover {
                color: #FF914D;
            }

            .autobox.active li {
                display: block;
            }

            .editor_level {
                display: none;
            }
        </style>
    @endpush
    <div class="row">
        <div class="col-md-12">
            {{ Breadcrumbs::render('user.stories.create') }}
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 style="padding: 24px 24px 0;">{{ $title ?? '' }}</h3>
                    <hr>
                </div>
                <div class="content">
                    <form action="{{ route("user.$table.store") }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-3" style="padding: 16px">
                                <label for="file_image" style="width: 100%; height: 100%;">
                                    <div class="image_box">
                                        <i class="fa fa-photo photo_img"></i>
                                        @if ($errors->any())
                                            <span class="text-danger"
                                                  style="position: absolute; bottom: 12px;">{{ $errors->first('image') }}</span>
                                        @endif
                                    </div>
                                </label>
                                <input type="file" accept="image/*" style="display: none;" id="file_image"
                                       name="image">
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-target="#collapseOne" href="#"
                                                       data-toggle="collapse-hover">
                                                        <div class="form-group">
                                                            <label for="">Thể loại</label>
                                                            <b class="caret"></b>
                                                        </div>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseOne" class="panel-collapse collapse collapse-hover">
                                                <div class="panel-body">
                                                    <div class="row">
                                                        @foreach ($categories as $category)
                                                            <div class="col-sm-2">
                                                                <div class="checkbox">
                                                                    <input class="filter-input"
                                                                           id="categories{{ $category->id }}"
                                                                           name="categories[]" type="checkbox"
                                                                           value="{{ $category->id }}"
                                                                           @if(old('categories') !== null)
                                                                               @if(in_array($category->id, old('categories')))
                                                                                   checked
                                                                            @endif
                                                                            @endif
                                                                    >
                                                                    <label for="categories{{ $category->id }}"
                                                                           style="padding-left: 24px">
                                                                        {{ $category->name }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($errors->any())
                                            <span class="text-danger">{{ $errors->first('categories') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="control-label">Tên</label>
                                            <input type="text" class="form-control" name="name" id="name"
                                                   value="{{ old('name') }}">
                                            @if ($errors->any())
                                                <span class="text-danger">{{ $errors->first('name') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="status" class="control-label">Tình trạng</label>
                                            <select name="status" id="status" class="form-control filter-input"
                                                    style="margin-left: 6px">
                                                @foreach ($status as $value => $name)
                                                    <option value="{{ $value }}"
                                                            @if((string)$value === old('status'))
                                                                selected
                                                            @endif
                                                    >{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->any())
                                                <span class="text-danger">{{ $errors->first('status') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-md-offset-1">
                                        <div class="form-group">
                                            <label for="level" class="control-label">Phân loại</label>
                                            <select name="level" id="level" class="form-control filter-input"
                                                    style="margin-left: 6px">
                                                @foreach ($level as $value => $name)
                                                    <option value="{{ $value }}"
                                                            @if((string)$value === old('level'))
                                                                selected
                                                            @endif
                                                    >{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->any())
                                                <span class="text-danger">{{ $errors->first('level') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="author" class="control-label">Tác giả</label>
                                            <input type="text" class="form-control" name="author" id="author"
                                                   value="{{ old('author') }}" autocomplete="off">
                                            <div class="autobox" id="author_list">
                                                <ul>
                                                </ul>
                                            </div>
                                            @if ($errors->any())
                                                <span class="text-danger">{{ $errors->first('author') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group editor_level">
                                            <label for="author_2" class="control-label">Người chỉnh sửa</label>
                                            <input type="text" class="form-control" name="author_2" id="author_2"
                                                   value="{{ old('author_2') }}" autocomplete="off">
                                            <div class="autobox" id="author_2_list">
                                                <ul>
                                                </ul>
                                            </div>
                                            @if ($errors->any())
                                                <span class="text-danger">{{ $errors->first('author_2') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="describe" class="control-label">Mô tả</label>
                                                <textarea class="form-control" name="descriptions" id="describe">
                                    {{ old('descriptions') }}
                                    </textarea>
                                                @if ($errors->any())
                                                    <span
                                                            class="text-danger">{{ $errors->first('descriptions') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer text-center">
                            <button class="btn btn-primary">Thêm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            const imageBox = document.querySelector('.image_box');
            const image = document.querySelector('#file_image');

            image.onchange = function () {
                imageBox.innerHTML = '<i class="fa fa-photo photo_img"></i>';
                if (this.files[0] !== undefined) {
                    let url = URL.createObjectURL(this.files[0]);
                    let img = `<img src="${url}" style="width:100%; object-fit: cover;">`;
                    imageBox.innerHTML = img;
                }
            }
        </script>
        <script>
            @php
                $author = json_encode($authorArray);
                echo "let author_list = $author";
            @endphp;

            @php
                $author2 = json_encode($author2Array);
                echo "let author_2_list = $author2";
            @endphp;

            const author = document.querySelector('#author')
            const authorBox = document.querySelector('#author_list');
            showAuthorList(author, authorBox, author_list);

            const author2 = document.querySelector('#author_2')
            const author2Box = document.querySelector('#author_2_list');

            showAuthorList(author2, author2Box, author_2_list);

            function showAuthorList(input, box, listAuthor) {
                input.onkeyup = (e) => {
                    let checkData = e.target.value
                    let dataArr = []

                    if (checkData) {
                        dataArr = listAuthor.filter((data) => {
                            return data.toLocaleLowerCase().startsWith(checkData.toLocaleLowerCase())
                        })
                        dataArr = dataArr.map((data) => {
                            return data = '<li>' + data + '</li>'
                        })
                        box.classList.add('active')
                        showSearch(dataArr)
                        let liItem = box.querySelectorAll('li')
                        for (let i = 0; i < liItem.length; i++) {
                            liItem[i].addEventListener("click", function () {
                                input.value = liItem[i].innerHTML
                                box.classList.remove('active')
                            })
                        }
                    } else {
                        box.classList.remove('active')
                    }
                }

                function showSearch(list) {
                    let listData
                    if (!list.length) {
                        listData = '<li>' + input.value + '</li>'
                    } else {
                        listData = list.join('')
                    }
                    box.innerHTML = listData
                }
            }
        </script>

        <script>
            const levelSelect = document.querySelector('#level');
            let editor_level = document.querySelector('.editor_level')
            if (levelSelect.value === '1') {
                editor_level.style.display = 'block';
            } else {
                editor_level.style.display = 'none';
            }
            levelSelect.onchange = function () {
                if (levelSelect.value === '1') {
                    editor_level.style.display = 'block';
                } else {
                    editor_level.style.display = 'none';
                }
            }
        </script>

        <script src="https://cdn.ckeditor.com/ckeditor5/35.2.0/classic/ckeditor.js"></script>
        <script>
            ClassicEditor.create(document.querySelector('#describe'))
                .then(editor => {
                    console.log(editor);
                })
                .catch(error => {
                    console.error(error);
                });
        </script>
    @endpush
@endsection
