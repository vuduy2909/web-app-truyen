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
            {{ Breadcrumbs::render('admin.chapters.index') }}
        </div>
    </div>
    <div class="row">
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
                                        <th>chương</th>
                                        <th>Tên chương</th>
                                        <th>Tên truyện</th>
                                        <th>Trạng thái</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $chapter)
                                        <tr>
                                            <td>{{ $chapter->number }}</td>
                                            <td>{{ $chapter->name }}</td>
                                            <td>{{ $chapter->story }}</td>
                                            <td>{{ $chapter->pin_name }}</td>

                                            <td class="td-actions text-right">
                                                <div style="display: flex;">
                                                    <a rel="tooltip" data-original-title="Xem"
                                                        href="{{ route("admin.stories.chapters.show",
                                                                ['id' => $chapter->story_id, 'number' => $chapter->number]) }}"
                                                        class="btn btn-simple btn-info btn-icon table-action">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <form action="{{ route("admin.stories.chapters.approve",
                                                                ['id' => $chapter->story_id, 'number' => $chapter->number]) }}"
                                                        method="post">
                                                        @csrf
                                                        <button rel="tooltip" data-original-title="duyệt"
                                                            class="btn btn-simple btn-success btn-icon
                                                                table-action">
                                                            <i class="fa fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route("admin.stories.chapters.un_approve",
                                                                ['id' => $chapter->story_id, 'number' => $chapter->number]) }}"
                                                          method="post">
                                                        @csrf
                                                        <button rel="tooltip" data-original-title="không duyệt"
                                                                class="btn btn-simple btn-warning btn-icon
                                                                    table-action">
                                                            <i class="fa fa-ban"></i>
                                                        </button>
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
        </script>
    @endpush
@endsection
