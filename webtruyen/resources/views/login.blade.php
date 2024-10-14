<html lang="en" class="perfect-scrollbar-on">

<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/png" href="../../assets/img/favicon.ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title>Login</title>

    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <meta name="viewport" content="width=device-width">

    <!-- Bootstrap core CSS     -->
    <link href="{{ asset('admin_asset/css/bootstrap.min.css') }}" rel="stylesheet" />

    <!--  Light Bootstrap Dashboard core CSS    -->
    <link href="{{ asset('admin_asset/css/light-bootstrap-dashboard.css? v=1.4.1') }}" rel="stylesheet" />

    <!--  css for Demo Purpose, don't include it in your project     -->
    <link href="{{ asset('admin_asset/css/demo.css') }}" rel="stylesheet" />


    <!--     Fonts and icons     -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="{{ asset('admin_asset/css/pe-icon-7-stroke.css') }}" rel="stylesheet" />

</head>

<body>
@if (session()->has('error'))
    <div data-notify="container" class="col-xs-11 col-sm-4 alert
                alert-danger alert-with-icon animated fadeInDown"
         role="alert" data-notify-position="top-center" id="errorMessage"
         style="display: inline-block; margin: 0px auto; position: fixed;
transition: all 0.5s ease-in-out;
 z-index: 1031; top: 20px; left: 0px; right: 0px;">
        <button type="button" aria-hidden="true" class="close" data-notify="dismiss"
                style="position: absolute; right: 10px;
    top: 50%; margin-top: -13px;
    z-index: 1033;">×</button>
        <span data-notify="icon" class="pe-7s-gift"></span>
        <span data-notify="title"></span>
        <span data-notify="message"> {{ session()->get('error') }}</span>
        <a href="#" target="_blank" data-notify="url"></a>
    </div>
    <script>
        const btnClose = document.querySelector('.alert#errorMessage>button.close')
        btnClose.onclick = function() {
            document.querySelector('.alert#errorMessage').style = 'top: -500%; opacity: 0';
        }
    </script>
@endif
    <nav class="navbar navbar-transparent navbar-absolute">
        <div class="container">
            <div class="collapse navbar-collapse">

                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="{{ route('register') }}">
                            Đăng ký
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="wrapper wrapper-full-page">
        <div class="full-page login-page" data-color="azure"
            data-image="{{ asset('admin_asset/img/full-screen-image-1.jpg') }}">

            <!--   you can change the color of the filter page using: data-color="blue | azure | green | orange | red | purple" -->
            <div class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-md-offset-4 col-sm-offset-3">
                            <form method="post" action="{{ route('logining') }}">
                                @csrf
                                <!--   if you want to have the card without animation please remove the ".card-hidden" class   -->
                                <div class="card card-hidden">
                                    <div class="header text-center">Đăng nhập</div>

                                    <div class="content">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" placeholder="Nhập email" name="email"
                                                class="form-control" value="{{ old('email') }}">
                                                @if ($errors->any())
                                                    <span>{{ $errors->first('email') }}</span>
                                                @endif
                                        </div>
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" name="password" placeholder="Password"
                                                class="form-control">
                                            @if ($errors->any())
                                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="footer text-center">
                                        <button type="submit" class="btn btn-fill btn-warning btn-wd">Đăng
                                            nhập</button>
                                    </div>
                                    <div class="text-center">
                                        <h5>Đăng nhập với</h5>
                                        <a href="{{ route('auth.redirect', 'google') }}"
                                            class="btn btn-social btn-round btn-google mb-2">
                                            <i class="fa fa-google"></i>
                                        </a>
                                        <a class="btn btn-social btn-round btn-facebook"
                                           href="{{ route('auth.redirect', 'facebook') }}"
                                        >
                                            <i class="fa fa-facebook-square"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <p class="copyright pull-right">
                ©
                <script>
                    document.write(new Date().getFullYear())
                </script>2022 <a href="http://www.creative-tim.com">Creative Tim</a>, made with love for
                a better web
            </p>
        </div>
        </footer>

    </div>


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
    <script type="text/javascript">
        $().ready(function() {
            lbd.checkFullPageBackgroundImage();

            setTimeout(function() {
                // after 1000 ms we add the class animated to the login/register card
                $('.card').removeClass('card-hidden');
            }, 700)
        });
    </script>


</body>

</html>
