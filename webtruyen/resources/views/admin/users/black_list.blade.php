@extends('layout.admin_and_user_page.master')
@section('main')
    @push('css')
        <style>
            [class^="pe-"] {
                font-size: 30px;
            }
        </style>
    @endpush
    <div class="row">
        <div class="col-md-12">
            {{ Breadcrumbs::render('admin.users.black_list') }}
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="content">
                        <form action="" class="form-inline" id="form-filter">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="gender">Giới tính</label>
                                        <select name="gender" id="gender" class="form-control select-filter">
                                            <option value="">All</option>
                                            @foreach ($genders as $value => $gender)
                                                <option value="{{ $value }}"
                                                        @if($genderFilter === (string)$value) selected @endif
                                                >{{ $gender }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="level_id">Cấp bậc</label>
                                        <select name="level_id" id="level_id" class="form-control select-filter">
                                            <option value="">All</option>
                                            @foreach ($levels as $level)
                                                <option value="{{ $level->id }}"
                                                        @if($levelFilter === (string)$level->id) selected @endif
                                                >{{ $level->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route("admin.$table.black_list") }}" class="btn btn-default btn-fill">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="bootstrap-table">
                        <div class="content table-responsive table-full-width">
                            <table class="table table-hover table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Info</th>
                                    <th>Cấp bậc</th>
                                    <th>Ảnh đại diện</th>
                                    <th>Ngày vào sổ</th>
                                    <th>Xử lý</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($data as $user)
                                    <tr>
                                        <td>{{ '###'. $user->id }}</td>
                                        <td>
                                            <div style="display: flex">
                                                <span style="margin-right: 8px">{{ $user->name }}</span> -
                                                <span style="margin-left: 8px">{{ $user->gender_name }}</span>
                                            </div>
                                            <div>
                                                <a href="mailto:{{ $user->email }}"
                                                   style="margin: 0 10px">{{ $user->email }}</a>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $user->level->name }}
                                        </td>
                                        <td>
                                            <style>
                                                img:hover {
                                                    transform: scale(1.2);
                                                    transition: all 0.3s ease;
                                                }
                                            </style>
                                            @if (isset($user->avatar))
                                                <a href="{{ $user->avatar_url }}" target="_blank">

                                                    <img style="width: 50px; height: 50px; object-fit: cover;"
                                                         src="{{ $user->avatar_url }}">
                                                </a>
                                            @else
                                                <img style="width: 50px; object-fit: cover;"
                                                     src="{{ asset('img/no_face.png') }}">
                                            @endif
                                        </td>
                                        <td>
                                            {{ $user->deleted_at }}
                                        </td>
                                        <td>
                                            <div class="" style="display: flex;">
                                                <form action="{{ route("admin.$table.restore", $user->id) }}"
                                                      method="post">
                                                    @csrf
                                                    <button class="pe-7s-refresh-2 text-success"
                                                            style="border: none; background: transparent"></button>
                                                </form>
                                                <form action="{{ route("admin.$table.kill", $user->id) }}"
                                                      method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <button class="pe-7s-trash text-danger"
                                                            style="border: none; background: transparent"></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="fixed-table-pagination">
                            <div class="pull-right pagination">
                                {{ $data->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            const selectFilters = document.querySelectorAll('#form-filter .select-filter')
            const formFilter = document.querySelector('#form-filter')
            selectFilters.forEach((selectFilter) => {
                selectFilter.onchange = () => {
                    formFilter.submit();
                }
            })
        </script>
    @endpush
@endsection
