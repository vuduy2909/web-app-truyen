@php use App\Enums\UserGenderEnum; @endphp
@extends('layout.admin_and_user_page.master')
@section('main')
    @push('css')
        <style>
            .card {
                max-width: 800px;
                margin: auto;
            }
        </style>
    @endpush
    <div class="row">
        <div class="col-md-12">
            {{ Breadcrumbs::render('admin.users.create') }}
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
                    <form action="{{ route("admin.$table.store") }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name" class="control-label">Tên</label>
                            <input type="text" class="form-control" name="name" id="name"
                                   value="{{ old('name') }}">
                            @if ($errors->any())
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="control-label">Email</label>
                                    <input type="email" class="form-control" name="email" id="email"
                                           value="{{ old('email') }}">
                                    @if ($errors->any())
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="control-label">Mật khẩu</label>
                                    <input type="password" class="form-control" name="password" id="password"
                                           value="{{ old('password') }}">
                                    @if ($errors->any())
                                        <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gender" class="control-label">Giới tính</label>
                                    <select name="gender" id="gender" class="form-control">
                                        @foreach ($genders as $value => $gender)
                                            <option value="{{ $value }}">{{ $gender }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->any())
                                        <span class="text-danger">{{ $errors->first('gender') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="level_id" class="control-label">Cấp bậc</label>
                                    <select name="level_id" id="level_id" class="form-control">
                                        @foreach ($levels as $level)
                                            <option value="{{ $level->id }}">{{ $level->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->any())
                                        <span class="text-danger">{{ $errors->first('level_id') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="avatar" class="control-label">Ảnh đại diện</label>
                                <input type="file" accept="image/*" class="form-control " name="avatar" id="avatar">
                                @if ($errors->any())
                                    <span class="text-danger">{{ $errors->first('avatar') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="avatar-container">

                                </div>
                            </div>
                        </div>
                        <div class="footer text-center" style="margin-top: 24px">
                            <button class="btn btn-primary">Thêm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        const avatarContainer = document.querySelector('.avatar-container');
        const avatar = document.querySelector('#avatar');

        avatar.onchange = function () {
            avatarContainer.innerHTML = '';
            let url = URL.createObjectURL(this.files[0]);
            let img = `<img src="${url}" style="width:200px;">`;
            avatarContainer.innerHTML = img;
        }
    </script>
@endsection
