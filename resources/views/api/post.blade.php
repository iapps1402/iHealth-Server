<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/css/font.css"/>
    <title>{{ $post->title }}</title>
    <!-- Font Awesome Min CSS -->
    <link rel="stylesheet" href="/assets/css/fontawesome.min.css">
</head>
<style>
    body {
        margin: 0;
        padding: 0;
        direction: {{ $lang == 'en' ? 'ltr' : 'rtl' }};
        text-align: {{ $lang == 'en' ? 'left' : 'right' }};
        font-family: IRANSans, Tahoma, serif;
    }

    a, b {
        color: #0170bf;
    }

    .content {
        margin-top: 10px;
    }

    .container {
        margin: 15px;
    }

    img.picture {
        width: 100%;
        height: auto;
        max-height: 250px;
        border-radius: 15px;
    }

    .clearfix {
        clear: both;
    }

    .container i {
        padding-{{ $lang == 'en' ? 'right' : 'left' }}: 5px;
    }

    .container .item {
        float: {{ $lang == 'en' ? 'left' : 'right' }};
        padding: 5px;
        color: #777777;
    }

</style>
<body>

<div class="container">
    <img src="{{ $post->picture->absolute_path }}" class="picture" alt="{{ $post->title }}"/>
    <div style="float: {{ $lang == 'en' ? 'left' : 'right' }}">
        <div class="item"><i class="fas fa-user"></i>{{ $post->author->full_name }} </div>
        <div class="item"><i
                class="fas fa-calendar"></i>{{ $lang == 'fa' ? \Morilog\Jalali\Jalalian::fromDateTime($post->created_at)->format('%A, %d %B %Y') : \Carbon\Carbon::parse($post->created_at)->format('Y/m/d') }}
        </div>
        <div class="item"><i class="fas fa-eye"></i>{{ $post->views . ($lang == 'fa' ? ' بازدید' : ' views') }} </div>
        <div class="clearfix"></div>

    </div>

    <div class="clearfix"></div>

    <div class="content">
        {!! $post->text !!}
    </div>
</div>
</body>

</html>
