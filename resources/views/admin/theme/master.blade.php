<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <meta content="ThemeDesign" name="https://stacklearn.ir"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

    @include('meta::manager', [
   'title'         => $page['title'],
   'description'   => $page['description'],
   'image'         => isset($page['image']) ? $page['image'] : null,
])

    <link rel="shortcut icon" href="{{ asset($themePrefix . 'images/favicon.ico') }}">
@yield('header')
<!-- morris css -->
    <link rel="stylesheet" href="{{ asset($themePrefix . 'plugins/morris/morris.css') }}">

    <link rel="stylesheet" href="{{ asset($themePrefix . 'plugins/select2/css/select2.min.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link href="{{ asset($themePrefix . 'css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset($themePrefix . 'css/icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset($themePrefix . 'css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset($themePrefix . 'plugins/jalalidatepicker/jalalidatepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset($themePrefix . 'plugins/ion-rangeslider/css/ion.rangeslider.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset($themePrefix . 'plugins/ckeditor/ckeditor.js') }}" type="text/javascript"></script>
</head>


<body class="fixed-left">
@yield('modal')
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

<!-- Begin page -->
<div id="wrapper">

    <!-- ========== Left Sidebar Start ========== -->
    <div class="left side-menu">
        <button type="button" class="button-menu-mobile button-menu-mobile-topbar open-left waves-effect">
            <i class="mdi mdi-close"></i>
        </button>

        <div class="left-side-logo d-block d-lg-none">
            <div class="text-center">

                <a href="{{ Route('admin_dashboard') }}" class="logo"><img
                        src="{{ asset($themePrefix . 'images/logo_dark.png') }}" height="20" alt="logo"></a>
            </div>
        </div>

        <div class="sidebar-inner slimscrollleft">

            <div id="sidebar-menu">
                <ul>
                    <li class="menu-title"></li>

                    <li>
                        <a href="{{ Route('admin_dashboard') }}" class="waves-effect">
                            <i class="dripicons-home"></i>
                            <span> داشبورد </span>
                        </a>
                    </li>

                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-store"></i> <span> مدیریت غذاها </span>
                            <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('admin_food_add') }}">افزودن</a></li>
                            <li><a href="{{ route('admin_food_manage') }}">مدیریت</a></li>
                            <li><a href="{{ route('admin_food_category_manage') }}">دسته بندی</a></li>
                            <li><a href="{{ route('admin_food_suggestion_manage') }}">پیشنهاد غذا</a></li>
                        </ul>
                    </li>

                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-blog"></i>
                            <span> وبلاگ </span>
                            <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('admin_blog_post_add') }}">افزودن</a></li>
                            <li><a href="{{ route('admin_blog_post_manage') }}">مدیریت</a></li>
                            <li><a href="{{ route('admin_blog_post_category', ['id' => 'root']) }}">دسته بندی</a></li>
                        </ul>
                    </li>

                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-photo"></i> <span> اسلایدشو </span>
                            <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('admin_slider_add') }}">افزودن</a></li>
                            <li><a href="{{ route('admin_slider_manage') }}">مدیریت</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="{{ route('admin_upload_manage') }}" class="waves-effect">
                            <i class="dripicons-upload"></i>
                            <span> آپلود </span>
                        </a>
                    </li>

                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-folder"></i> <span> برنامه تغذیه </span>
                            <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('admin_diet_program_add') }}">ارسال برنامه</a></li>
                            <li><a href="{{ route('admin_diet_program_manage') }}">مدیریت</a></li>
                        </ul>
                    </li>

                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-user"></i>
                            <span> کاربران </span>
                            <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('admin_user_add') }}">افزودن</a></li>
                            <li><a href="{{ route('admin_user_manage') }}">مدیریت</a></li>
                        </ul>
                    </li>

                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-user"></i>
                            <span> پشتیبان </span>
                            <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('admin_support_add') }}">افزودن</a></li>
                            <li><a href="{{ route('admin_support_manage') }}">مدیریت</a></li>
                        </ul>
                    </li>

                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-user"></i>
                            <span> فعالیت </span>
                            <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('admin_activity_add') }}">افزودن</a></li>
                            <li><a href="{{ route('admin_activity_manage') }}">مدیریت</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('admin_application_manage') }}" class="dripicons-view-apps">
                            <span> اپلیکشن </span>
                        </a>
                    </li>

                </ul>
            </div>
            <div class="clearfix"></div>
        </div> <!-- end sidebarinner -->
    </div>
    <!-- Left Sidebar End -->

    <!-- Start right Content here -->

    <div class="content-page">
        <!-- Start content -->
        <div class="content">

            <!-- Top Bar Start -->
            <div class="topbar">

                <div class="topbar-left	d-none d-lg-block">
                    <div class="text-center">
                        <a href="{{ Route('admin_dashboard') }}" class="logo"><img
                                src="{{ asset($themePrefix . 'images/logo.png') }}" height="22" alt="logo"></a>
                    </div>
                </div>

                <nav class="navbar-custom">

                    <!-- Search input -->
                    <div class="search-wrap" id="search-wrap">
                        <div class="search-bar">
                            <input class="search-input" type="search" placeholder="جستجو"/>
                            <a href="#" class="close-search toggle-search" data-target="#search-wrap">
                                <i class="mdi mdi-close-circle"></i>
                            </a>
                        </div>
                    </div>

                    <ul class="list-inline float-right mb-0">
                        {{--                        <li class="list-inline-item dropdown notification-list">--}}
                        {{--                            <a class="nav-link waves-effect toggle-search" href="#" data-target="#search-wrap">--}}
                        {{--                                <i class="mdi mdi-magnify noti-icon"></i>--}}
                        {{--                            </a>--}}
                        {{--                        </li>--}}

                        <li class="list-inline-item dropdown notification-list">
                            <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#"
                               role="button"
                               aria-haspopup="false" aria-expanded="false">
                                <i class="mdi mdi-bell-outline noti-icon"></i>
                                <span class="badge badge-danger badge-pill noti-icon-badge">3</span>
                            </a>
                            <div
                                class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-menu-lg dropdown-menu-animated">
                                <!-- item-->
                                <div class="dropdown-item noti-title">
                                    <h5>اعلانات (3)</h5>
                                </div>

                                <div class="slimscroll-noti">
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item active">
                                        <div class="notify-icon bg-success"><i class="mdi mdi-cart-outline"></i></div>
                                        <p class="notify-details"><b>سفارش شما قرار داده شده است</b><span
                                                class="text-muted">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است.</span>
                                        </p>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <div class="notify-icon bg-danger"><i class="mdi mdi-message-text-outline"></i>
                                        </div>
                                        <p class="notify-details"><b>پیام جدید دریافت شد</b><span class="text-muted">شما 87 پیام خوانده نشده دارید</span>
                                        </p>
                                    </a>
                                    <!-- All-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-all">
                                        مشاهده همه
                                    </a>

                                </div>
                        </li>


                        <li class="list-inline-item dropdown notification-list nav-user">
                            <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#"
                               role="button"
                               aria-haspopup="false" aria-expanded="false">
                                <img src="{{ asset($themePrefix . 'images/users/avatar-6.jpg') }}" alt="user"
                                     class="rounded-circle">
                                <span class="d-none d-md-inline-block ml-1">{{ $admin->user->full_name }} <i
                                        class="mdi mdi-chevron-down"></i> </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated profile-dropdown">
                                <a class="dropdown-item" href="#"><i class="dripicons-user text-muted"></i> پروفایل</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ Route('admin_logout') }}"><i
                                        class="dripicons-exit text-muted"></i> خروج</a>
                            </div>
                        </li>

                    </ul>

                    <ul class="list-inline menu-left mb-0">
                        <li class="list-inline-item">
                            <button type="button" class="button-menu-mobile open-left waves-effect">
                                <i class="mdi mdi-menu"></i>
                            </button>
                        </li>
                        {{--                        <li class="list-inline-item dropdown notification-list d-none d-sm-inline-block">--}}
                        {{--                            <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#"--}}
                        {{--                               role="button"--}}
                        {{--                               aria-haspopup="false" aria-expanded="false">--}}
                        {{--                                ایجاد جدید ترین <i class="mdi mdi-plus"></i>--}}
                        {{--                            </a>--}}
                        {{--                            <div class="dropdown-menu dropdown-menu-animated">--}}
                        {{--                                <a class="dropdown-item" href="#">عملیات</a>--}}
                        {{--                                <a class="dropdown-item" href="#">اقدام دیگری</a>--}}
                        {{--                                <a class="dropdown-item" href="#">چیز های دیگر</a>--}}
                        {{--                                <div class="dropdown-divider"></div>--}}
                        {{--                                <a class="dropdown-item" href="#">پیوند جدا شده</a>--}}
                        {{--                            </div>--}}
                        {{--                        </li>--}}
                        {{--                        <li class="list-inline-item notification-list d-none d-sm-inline-block">--}}
                        {{--                            <a href="#" class="nav-link waves-effect">--}}
                        {{--                                فعالیت--}}
                        {{--                            </a>--}}
                        {{--                        </li>--}}

                    </ul>

                </nav>

            </div>
            <!-- Top Bar End -->

            <div class="page-content-wrapper ">

                <div class="container-fluid">

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="page-title-box">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h4 class="page-title m-0">{{ $page['title'] }}</h4>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="float-right">
                                            @yield('buttons')
                                        </div>
                                    </div>
                                    <!-- end col -->
                                </div>
                                <!-- end row -->
                            </div>
                            <!-- end page-title-box -->
                        </div>
                    </div>
                    <!-- end page title -->

                    @yield('container')

                </div><!-- container fluid -->

            </div> <!-- Page content Wrapper -->

        </div> <!-- content -->

        <footer class="footer">
            ©
            <script>document.write(new Date().getFullYear())</script>
            <span class="d-none d-md-inline-block">طراحی توسط <a href="https://stacklearn.ir" target="_blank">استک کد</a></span>
        </footer>

    </div>
    <!-- End Right content here -->

</div>
<!-- END wrapper -->


<!-- jQuery  -->
<script src="{{ asset($themePrefix . 'js/jquery.min.js') }}"></script>
<script src="{{ asset($themePrefix . 'js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset($themePrefix . 'js/modernizr.min.js') }}"></script>
<script src="{{ asset($themePrefix . 'js/detect.js') }}"></script>
<script src="{{ asset($themePrefix . 'js/fastclick.js') }}"></script>
<script src="{{ asset($themePrefix . 'js/jquery.slimscroll.js') }}"></script>
<script src="{{ asset($themePrefix . 'js/jquery.blockUI.js') }}"></script>
<script src="{{ asset($themePrefix . 'js/waves.js') }}"></script>
<script src="{{ asset($themePrefix . 'js/jquery.nicescroll.js') }}"></script>
<script src="{{ asset($themePrefix . 'js/jquery.scrollTo.min.js') }}"></script>
<script type="text/javascript"
        src="{{ asset($themePrefix . 'plugins/jalalidatepicker/jalalidatepicker.min.js') }}"></script>

<!--Morris Chart-->
<script src="{{ asset($themePrefix . 'plugins/morris/morris.min.js') }}"></script>
<script src="{{ asset($themePrefix . 'plugins/raphael/raphael.min.js') }}"></script>

<!-- dashboard js -->
<script src="{{ asset($themePrefix . 'pages/dashboard.int.js') }}"></script>

<!-- App js -->
<script src="{{ asset($themePrefix . 'js/app.js') }}"></script>

<!-- Alertify js -->
<script src="{{ asset($themePrefix . 'plugins/alertify/js/alertify.js') }}"></script>

<script src="{{ asset($themePrefix . 'plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset($themePrefix . 'plugins/ion-rangeslider/js/ion.rangeslider.min.js') }}" type="text/javascript"></script>

<script src="https://cdn.jsdelivr.net/npm/jalaali-js/dist/jalaali.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jalaali-js/dist/jalaali.min.js"></script>

@yield('scripts')

<script>
    function numberFormat(x) {
        return x.toFixed().toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
</script>

</body>

</html>
