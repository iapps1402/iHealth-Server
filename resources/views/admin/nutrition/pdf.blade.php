<!DOCTYPE html>
<html lang="fa">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="shortcut icon" href="{{ asset($themePrefix . 'images/favicon.ico') }}">
    <link href="{{ asset($themePrefix . 'css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset($themePrefix . 'css/icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset($themePrefix . 'css/style.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset($themePrefix . 'plugins/morris/morris.css') }}">
    <title>{{ 'برنامه تغذیه ' . $program->user->full_name }}</title>
    <style>
        tr:nth-child(odd) {
            background: #fafafa;
        }

        span > i {
            text-align: center;
            font-size: 15pt;
            color: #ef8b28;
        }

        table.eventless tr:nth-child(2n+1) {
            background: inherit !important;
            border-bottom: 1px solid #e5e5e5 !important;
        }

        span > i {
            padding-right: 8px;
            display: inline-flex;
            align-items: center;
            vertical-align: center;
            border-radius: 50%;
            text-align: center;
            width: 40px;
            height: 40px;
            background: #fafafa;
            float: right;
            margin-left: 10px;
        }

        .progress-text {
            padding-top: 8px;
            font-weight: bold;
            text-align: center;
            color: #333333;
        }

        .progress-title {
            padding-bottom: 8px;
            text-align: center;
            color: #333333;
        }

    </style>
</head>
<body>
<div class="container">
    <div class="mt-4">
        <h4 class="text-center iteration">{{ $program->user->full_name }}</h4>
        <div class="text-center text-muted iteration">{{ $program->created_at_shamsi }}</div>
    </div>
    <div class="mt-4">

        <div class="card">
            <div class="card-body">

                <div class="mb-4 p-2" style="background: #f1ebf5;border-radius: 4px">
                    <div class="text-center font-32 iteration" style="font-weight: bolder">{{ $program->calorie }}</div>
                    <div class="text-center text-muted">کالری</div>
                </div>


                <div class="row">

                    <div class="col col-4">
                        <div class="progress-title">پروتئین</div>
                        <div class="progress-bar progress" style="background: #27ce77; height: 5px"></div>
                        <div class="progress-text iteration">{{ $program->protein }} گرم</div>
                    </div>

                    <div class="col col-4">
                        <div class="progress-title">کربوهیدرات</div>
                        <div class="progress-bar progress" style="background: #f38f64; height: 5px"></div>
                        <div class="progress-text iteration">{{ $program->carbs }} گرم</div>
                    </div>

                    <div class="col col-4">
                        <div class="progress-title">چربی</div>
                        <div class="progress-bar progress" style="background: #ef3725; height: 5px"></div>
                        <div class="progress-text iteration">{{ $program->fat }} گرم</div>
                    </div>

                </div>

            </div>
        </div>

        @if(!empty($program->note))
            <div class="card">
                <div class="card-header">
                    یادداشت برنامه تغذیه
                </div>
                <div class="card-body">
                    {{ $program->note }}
                </div>
            </div>
        @endif

        @if(count($program->supplements))
            <div class="card">
                <table class="table table-borderless eventless">
                    <thead>
                    <tr style="border-bottom: 1px solid #e5e5e5 !important;">
                        <th scope="col" width="40px">#</th>
                        <th scope="col">نام مکمل</th>
                        <th scope="col">مقدار مصرف</th>
                        <th scope="col">توضیحات</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($program->supplements as $supplement)
                        <tr>
                            <td style="font-size: 25px; color: #333333" class="iteration"><strong>{{ $loop->iteration }}
                                    .</strong></td>
                            <td>{{ $supplement->supplement->name_fa }}
                                <div class="text-muted">{{ $supplement->supplement->name_en }}</div>
                            </td>
                            <td class="iteration">{{ !empty($supplement->value) ? ($supplement->value . ' ' . $supplement->unit->name_fa) : $supplement->unit_text }}</td>
                            <td class="iteration"><p>{!! $supplement->text !!}</p>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>


            {{--            <div class="card">--}}
            {{--                <div class="card-body">--}}
            {{--                    <h4 class="mt-0 header-title mb-4 text-primary">مکمل ها</h4>--}}
            {{--                    <hr/>--}}
            {{--                    @foreach($program->supplements as $supplement)--}}
            {{--                        <div class="mb-2">--}}
            {{--                            <div>--}}
            {{--                                <div>--}}
            {{--                                    <span><i class="mdi mdi-food-fork-drink"></i></span>--}}
            {{--                                    <div style="float: right">--}}
            {{--                                        <strong>{{ $supplement->supplement->name_fa }}</strong><br>--}}
            {{--                                        <span class="text-muted">{{ $supplement->supplement->name_en }}</span>--}}


            {{--                                        <div class="mt-3">--}}
            {{--                                            {!! $supplement->text !!}--}}
            {{--                                        </div>--}}
            {{--                                    </div>--}}
            {{--                                    <div style="clear: both"></div>--}}

            {{--                                </div>--}}
            {{--                            </div>--}}

            {{--                        </div>--}}
            {{--                        <hr/>--}}
            {{--                    @endforeach--}}

            {{--                </div>--}}
            {{--            </div>--}}


        @endif


        @foreach($program->days as $day)
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-4" style="color: #00be64">{{ $day->day_fa }}</h4>
                    <hr/>
                    @foreach($day->meals as $meal)
                        <div class="mb-2">
                            <div>
                                <div>
                                    @php
                                        $proteinValue = 0;
                                        $calorieValue = 0;
                                        foreach ($meal->items as $item) {
                                            $proteinValue += $item->value * $item->unit->real_protein;
                                            $calorieValue += $item->value * $item->unit->real_calorie;
                                        }
                                    @endphp
                                    <span><i class="mdi mdi-food"></i></span>
                                    <div style="float: right">
                                        <strong>{{ $meal->name_fa }}</strong><br>
                                        <font class="iteration text-muted">{{ number_format($calorieValue) }}
                                            کالری، {{number_format( $proteinValue) }} گرم
                                            پروتئین</font>
                                    </div>
                                    <div style="clear: both"></div>


                                </div>
                            </div>

                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tbody>
                                @foreach($meal->items as $item)
                                    <tr>
                                        <th width="5%" class="iteration">{{ $loop->iteration }}</th>
                                        <td class="iteration">
                                            {{ number_format($item->value) . ' ' . $item->unit->name_fa . ' ' . $item->food->name_fa  }}
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>

                            </table>
                        </div>
                    @endforeach

                </div>
            </div>
        @endforeach
    </div>

    <div class="card">
        <div class="card-header">
            میزان کالری مصرفی
        </div>
        <div class="card-body">
            <div id="calorie-chart" class="morris-chart" style="height: 300px"></div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            میزان پروتئین مصرفی
        </div>
        <div class="card-body">
            <div id="protein-chart" class="morris-chart" style="height: 300px"></div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            میزان کربوهیدرات مصرفی
        </div>
        <div class="card-body">
            <div id="carbs-chart" class="morris-chart" style="height: 300px"></div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            میزان چربی مصرفی
        </div>
        <div class="card-body">
            <div id="fat-chart" class="morris-chart" style="height: 300px"></div>
        </div>
    </div>

    @foreach($cooks as $cook)
        <div class="card">
            <div class="card-header">
                طرز تهیه {{ $cook->food->name_fa }}
            </div>

            @if(count($cook->ingredients))
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col" class="col-1">#</th>
                        <th scope="col" class="col-5 text-center">نام</th>
                        <th scope="col" class="col-6 text-center">مقدار</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($cook->ingredients as $ingredient)
                        <tr>
                            <th scope="row" class="iteration">{{ $loop->iteration }}</th>
                            <td class="text-center">{{ $ingredient->name_fa }}</td>
                            <td class="text-center">{{ $ingredient->value_fa }}</td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            @endif

            <div class="card-body">
                @foreach($cook->instructions as $instruction)
                    <p><strong class="iteration">{{ $loop->iteration . '. ' }}</strong> {{ $instruction->text_fa }}</p>
                @endforeach
            </div>
        </div>
    @endforeach

</div>

<!-- jQuery  -->
<script src="{{ asset($themePrefix . 'js/jquery.min.js') }}"></script>
<script src="{{ asset($themePrefix . 'plugins/morris/morris.min.js') }}"></script>
<script src="{{ asset($themePrefix . 'plugins/raphael/raphael.min.js') }}"></script>

<script>
    let data = [
                @foreach($extra['calorie']['axis_x'] as $index => $x)
            {
                y: '{{ $x }}', a: {{ $extra['calorie']['axis_y'][$index] }}
            },
            @endforeach
        ],
        config = {
            data: data,
            xkey: 'y',
            ykeys: ['a'],
            labels: ['میزان مصرف کالری'],
            lineColors: ['#6015cc', '#46cd93']
        };
    config.element = 'calorie-chart';
    Morris.Area(config);
    config.element = 'line-chart';
    //  Morris.Bar(config);


    let proteinData = [
            @foreach($extra['protein']['axis_x'] as $index => $x)
        {
            y: '{{ $x }}', a: {{ $extra['protein']['axis_y'][$index] }}
        },
        @endforeach
    ]

    let proteinConfig = {
        data: proteinData,
        xkey: 'y',
        ykeys: ['a'],
        labels: ['میزان مصرف کربوهیدرات'],
        lineColors: ['#27d076', '#46cd93']
    };
    proteinConfig.element = 'protein-chart';
    Morris.Area(proteinConfig);
    proteinConfig.element = 'line-chart';
    // Morris.Bar(proteinConfig);

    data = [
            @foreach($extra['carbs']['axis_x'] as $index => $x)
        {
            y: '{{ $x }}', a: {{ $extra['carbs']['axis_y'][$index] }}
        },
        @endforeach
    ]

    config = {
        data: data,
        xkey: 'y',
        ykeys: ['a'],
        labels: ['میزان مصرف چربی'],
        lineColors: ['#ff824c', '#46cd93']
    };
    config.element = 'carbs-chart';
    Morris.Area(config);
    config.element = 'line-chart';
    // Morris.Bar(config);


    data = [
            @foreach($extra['fat']['axis_x'] as $index => $x)
        {
            y: '{{ $x }}', a: {{ $extra['fat']['axis_y'][$index] }}
        },
        @endforeach
    ]

    config = {
        data: data,
        xkey: 'y',
        ykeys: ['a'],
        labels: ['میزان مصرف چربی'],
        lineColors: ['#f03527', '#46cd93']
    };
    config.element = 'fat-chart';
    Morris.Area(config);
    config.element = 'line-chart';
    // Morris.Bar(config);

    String.prototype.toIndiaDigits = function () {
        var id = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        return this.replace(/[0-9]/g, function (w) {
            return id[+w]
        });
    }

    $(".iteration").each(function (i, item) {
        let value = $(this).text().toIndiaDigits()
        $(this).text(value)
    })
</script>

</body>
</html>
