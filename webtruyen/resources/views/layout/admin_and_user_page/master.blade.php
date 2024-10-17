<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <link rel="icon" href="{{ asset('/img/page/icon.png') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>

    <title>{{ env('APP_NAME') ?? '' }} - {{ $title ?? 'Quản lý' }}</title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <meta name="viewport" content="width=device-width"/>


    <!-- Bootstrap core CSS     -->
    <link href="{{ asset('admin_asset/css/bootstrap.min.css') }}" rel="stylesheet"/>

    <!--  Light Bootstrap Dashboard core CSS    -->
    <link href="{{ asset('admin_asset/css/light-bootstrap-dashboard.css? v=1.4.1') }}" rel="stylesheet"/>

    <!--  css for Demo Purpose, don't include it in your project     -->
    <link href="{{ asset('admin_asset/css/demo.css') }}" rel="stylesheet"/>


    <!--     Fonts and icons     -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="{{ asset('admin_asset/css/pe-icon-7-stroke.css') }}" rel="stylesheet"/>
    @stack('css')
</head>

<body>

<div class="wrapper">
    @include('layout.admin_and_user_page.sidebar')

    <div class="main-panel">

        @include('layout.admin_and_user_page.header')

        <div class="main-content">
            @if (session()->has('success'))
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-success alert-dismissible fade in"
                             style="max-width: 500px; margin: auto">
                            <a href="#" class="close" data-dismiss="alert" style="right: 0"
                               aria-label="close">&times;</a>
                            <strong>Thành công!</strong> {{ session()->get('success') }}
                            @php
                                session()->forget('success');
                            @endphp
                        </div>
                    </div>
                </div>
            @endif
            @if (session()->has('error'))
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-danger alert-dismissible fade in"
                             style="max-width: 500px; margin: auto">
                            <a href="#" class="close" data-dismiss="alert" style="right: 0"
                               aria-label="close">&times;</a>
                            <strong>Thất bại!</strong> {{ session()->get('error') }}
                            @php
                                session()->forget('error');
                            @endphp
                        </div>
                    </div>
                </div>
            @endif
            @yield('main')

        </div>

        @include('layout.admin_and_user_page.footer')

    </div>
</div>


</body>
<!--   Core JS Files  -->
<script src="{{ asset('admin_asset/js/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('admin_asset/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('admin_asset/js/perfect-scrollbar.jquery.min.js') }}" type="text/javascript"></script>


<!--  Forms Validations Plugin -->
<script src="{{ asset('admin_asset/js/jquery.validate.min.js') }}"></script>

<!--  Select Picker Plugin -->
<script src="{{ asset('admin_asset/js/bootstrap-selectpicker.js') }}"></script>

<!--  Checkbox, Radio, Switch and Tags Input Plugins -->
<script src="{{ asset('admin_asset/js/bootstrap-switch-tags.min.js') }}"></script>

<!--  Charts Plugin -->
<script src="{{ asset('admin_asset/js/chartist.min.js') }}"></script>

<!--  Notifications Plugin    -->
<script src="{{ asset('admin_asset/js/bootstrap-notify.js') }}"></script>

<!-- Sweet Alert 2 plugin -->
<script src="{{ asset('admin_asset/js/sweetalert2.js') }}"></script>

<!-- Wizard Plugin    -->
<script src="{{ asset('admin_asset/js/jquery.bootstrap.wizard.min.js') }}"></script>

<!--  bootstrap Table Plugin    -->
<script src="{{ asset('admin_asset/js/bootstrap-table.js') }}"></script>

<!-- Light Bootstrap Dashboard Core javascript and methods -->
<script src="{{ asset('admin_asset/js/light-bootstrap-dashboard.js?v=1.4.1') }}"></script>
@stack('js')

{{-- </body> --}}

</html>
