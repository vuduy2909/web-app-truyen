@extends('layout.admin_and_user_page.master')
@section('main')
    @push('css')
        <style>
            [class^="pe-"] {
                font-size: 30px;
            }
        </style>
    @endpush
    @auth
        @if(auth()->user()->level_id === 2 || auth()->user()->level_id === 3)
            <div class="row">
                <div class="col-md-12">
                    {{ Breadcrumbs::render('admin.categories.index') }}
                </div>
            </div>
        @endif
        @if(auth()->user()->level_id === 1)
            <div class="row">
                <div class="col-md-12">
                    {{ Breadcrumbs::render('user.categories.index') }}
                </div>
            </div>
        @endif
    @endauth
    <div class="row">
        @auth
            @if(auth()->user()->level_id === 2 || auth()->user()->level_id === 3)
                <a href="{{ route("admin.$table.create") }}" class="pe-7s-plus"
                   style="font-size: 40px; margin: 24px;"></a>
            @endif
        @endauth
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <div class="bootstrap-table">
                        <div class="content table-responsive table-full-width">
                            <table class="table table-hover table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên</th>
                                    <th>Mô tả</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($data as $category)
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->descriptions }}</td>
                                        @auth
                                            @if(auth()->user()->level_id === 2 || auth()->user()->level_id === 3)
                                                <td>
                                                    <div style="display: flex;">
                                                        <a href="{{ route("admin.$table.edit", $category->id) }}"
                                                           class="pe-7s-note">
                                                        </a>
                                                        <form action="{{ route("admin.$table.destroy", $category->id) }}"
                                                              method="post">
                                                            @csrf
                                                            @method('delete')
                                                            <button class="pe-7s-trash text-danger"
                                                                    style="border: none; background: transparent"></button>
                                                        </form>
                                                    </div>
                                                </td>
                                            @endif
                                        @endauth
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
@endsection
