<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/css/font.css"/>
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="/assets/css/fontawesome.min.css">
    <!-- Font Awesome Min CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>
<style>
    body {
        margin: 0;
        padding: 0;
        direction: {{ $lang == 'en' ? 'ltr' : 'rtl' }};
        text-align: {{ $lang == 'en' ? 'left' : 'right' }};
        font-family: IRANSans, Tahoma, serif;
    }

    a {
        text-decoration: none !important;
    }

    section.header {
        background: #8cc24a;
    }

    section.header a {
        color: white;
    }

    section.header nav {
        /*padding: 25px 20px;*/
    }

    section.header nav a.logo {
        font-size: 18pt;
    }

    .row-title {
        text-align: center;
        opacity: .5;
        color: white;
        font-weight: bold;
        font-size: 15pt;
    }

    ul li {
        color: white;
        line-height: 28px;
    }

    section.content {
        padding: 25px 20px;
    }

    section.content a.button {
        display: inline-block;
        border-radius: 5px;
        color: #333333;
        background: #f1f1f1;
        padding: 8px 15px;
    }

    @media only screen and (max-width: 450px) {
        .row .col {
            float: none;
            width: auto;
            padding: 20px 10px;
        }
    }

    @media (min-width: 576px) {

    }

</style>
<body>

<section class="header">
    <div class="col col-lg-8 col-md-8 col-sm-12 container">
        <nav>
            <a href="http://dopaminefit.ir" class="logo">دوپامین</a>
        </nav>
        <div class="row" style="margin-top: 40px">
            <div class="col-sm">
                One of three columns
            </div>
            <div class="col-sm">
                <div class="row-title">کاملترین نرم افزار تناسب اندام</div>
                <ul style="margin-top: 20px">
                    <li>بررسی مقدار کالری مصرفی به صورت روزانه</li>
                    <li>نمایش تغییرات دوره ای بر روی نمودار</li>
                    <li>پیشنهاد تغذیه برای تکمیل وعده های روزانه</li>
                    <li>مقالات سلامت</li>
                    <li>ارائه برنامه رژیمی</li>
                    <li>ارائه برنامه تمرینی</li>
                </ul>
            </div>
        </div>

    </div>

</section>

<section class="content">

    <div class="row">

        <div class="col col-lg-6">
            <div class="container">
                <div class="row">
                    <img src="/assets/images/android.png" width="80px" height="auto" alt="android"/>
                    <div style="margin-top: 15px">
                        <a class="button" href="#" style="margin: 2px">
                            <i class="fa fa-download"></i>
                            <span>دانلود مستقیم</span>
                        </a>
                        <a class="button" href="#" style="margin: 2px">
                            <i class="fa fa-download"></i>
                            <span>دانلود از کافه بازار</span>
                        </a>
                    </div>
                </div>

            </div>

        </div>


</section>

@if($lang == 'fa')
@endif

</body>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
</html>
