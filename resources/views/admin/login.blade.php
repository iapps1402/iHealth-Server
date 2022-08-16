<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta content="Admin Dashboard" name="description"/>
    <meta content="ThemeDesign" name="author"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

    @include('meta::manager', [
'title'         => $page['title'],
'description'   => $page['description'],
'image'         => isset($page['image']) ? $page['image'] : null,
])

    <link rel="shortcut icon" href="{{ asset('/themes/zinzer/assets/images/favicon.ico') }}">

    <link href="{{ asset('/themes/zinzer/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/themes/zinzer/assets/css/icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/themes/zinzer/assets/css/style.css') }}" rel="stylesheet" type="text/css">

</head>

<body class="fixed-left">

<!-- Loader -->
<div id="preloader">
    <div id="status">
        <div class="spinner">
            <div class="rect1"></div>
            <div class="rect2"></div>
            <div class="rect3"></div>
            <div class="rect4"></div>
            <div class="rect5"></div>
        </div>
    </div>
</div>

<div class="account-pages">

    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 offset-lg-1">
                <div class="text-left">
                    <div>
                        <a href="{{ Route('admin_login') }}" class="logo logo-admin"><img src="{{ asset('/themes/zinzer/assets/images/logo_dark.png') }}"
                                                                          height="28" alt="logo"></a>
                    </div>
                    <h5 class="font-14 text-muted mb-4">پنل ادمین اپلیکیشن دوپامین</h5>
                    <p class="text-muted mb-4">ورود مجاز فقط برای مدیران و نویسندگان اپلیکیشن</p>

                    <h5 class="font-14 text-muted mb-4">نکات :</h5>
                    <div>
                        <p><i class="mdi mdi-arrow-right text-primary mr-2"></i>در صورتی که اشتباها به این صفحه هدایت شده اید، این صفحه را ترک نمایید.</p>
                        <p><i class="mdi mdi-arrow-right text-primary mr-2"></i>در صورت مشکل در ورود به حساب با طراح اپلیکیشن ( <a href="https://stacklearn.ir">استک کد</a> ) تماس بگیرید.</p>
                        <p><i class="mdi mdi-arrow-right text-primary mr-2"></i><a href="https://stacklearn.ir">استک لرن</a></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card mb-0">
                    <div class="card-body">

                        <div class="p-2">
                            <h4 class="text-muted float-right font-18 mt-4">ورود</h4>
                            <div>
                                <a href="{{ Route('admin_login') }}" class="logo logo-admin"><img src="{{ asset('/themes/zinzer/assets/images/logo_dark.png') }}" height="28" alt="logo"></a>
                            </div>
                        </div>

                        @if(Session::has('success'))
                            <div class="alert alert-success" role="alert">
                                <strong>انجام شد!</strong> {{ Session('success') }}
                            </div>
                        @endif

                        @foreach($errors->all() as $error)
                            <div class="alert alert-danger" role="alert">
                                <strong>خطا!</strong> {{ $error }}</div>
                        @endforeach

                        <div class="p-2">
                            <form class="form-horizontal m-t-20" action="" method="post">
                                {{ csrf_field() }}
                                <div class="form-group row">
                                    <div class="col-12">
                                        <input class="form-control" type="text" name="username" required="" placeholder="نام کاربری">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-12">
                                        <input class="form-control" type="password" name="password" required="" placeholder="رمز عبور">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-12">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="remember" id="customCheck1">
                                            <label class="custom-control-label" for="customCheck1">مرا به خاطر داشته باش</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group text-center row m-t-20">
                                    <div class="col-12">
                                        <button class="btn btn-primary btn-block waves-effect waves-light" type="submit">ورود</button>
                                    </div>
                                </div>

                                <div class="form-group m-t-10 mb-0 row">
                                    <div class="col-sm-7 m-t-20">
                                        <a href="#" class="text-muted"><i class="mdi mdi-lock"></i> فراموشی رمز عبور؟</a>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>
</div>



<!-- jQuery  -->
<script src="{{ asset('/themes/zinzer/assets/js/jquery.min.js') }}"></script>
                                    <script
                                        src="{{ asset('/themes/zinzer/assets/js/bootstrap.bundle.min.js') }}"></script>
                                    <script src="{{ asset('/themes/zinzer/assets/js/modernizr.min.js') }}"></script>
                                    <script src="{{ asset('/themes/zinzer/assets/js/detect.js') }}"></script>
                                    <script src="{{ asset('/themes/zinzer/assets/js/fastclick.js') }}"></script>
                                    <script src="{{ asset('/themes/zinzer/assets/js/jquery.slimscroll.js') }}"></script>
                                    <script src="{{ asset('/themes/zinzer/assets/js/jquery.blockUI.js') }}"></script>
                                    <script src="{{ asset('/themes/zinzer/assets/js/waves.js') }}"></script>
                                    <script src="{{ asset('/themes/zinzer/assets/js/jquery.nicescroll.js') }}"></script>
                                    <script
                                        src="{{ asset('/themes/zinzer/assets/js/jquery.scrollTo.min.js') }}"></script>

                                    <!-- App js -->
                                    <script src="{{ asset('/themes/zinzer/assets/js/app.js') }}"></script>

</body>

</html>
