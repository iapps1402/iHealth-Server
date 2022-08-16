@section('container')
    <div class="row">
        <div class="col col-12 col-md-6 col-lg-6">

            <div class="card card-default">
                <div class="card-header">اطلاعات کلی</div>
                <div class="card-body">

                    <div>نام کاربر: <span>{{ $program->user->full_name }}</span></div>
                    <hr/>
                    <div>
                        شماره تماس / ایمیل: <span>{{ $program->user->contact }}</span>
                    </div>
                    <hr/>

                    <div>
                        برنامه نویس : <span>{{ $program->writer->full_name }}</span>
                    </div>
                    <hr/>

                    @if(!empty($program->note))
                        <div>
                            یادداشت برای مشتری: <span>{{ $program->note }}</span>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <div class="col col-12 col-md-6 col-lg-6">
            <div class="card card-default">
                <div class="card-header">رژیم غذایی</div>
                <div class="card-body">

                    @foreach($program->days as $day)
                        <div>
                            <b>{{ 'روز ' . $day->day_fa }}</b>

                            @foreach($day->meals as $meal)
                                <div>{{ $meal->name_fa }}:
                                    @foreach($meal->items as $item)
                                        <span
                                            class="btn m-2 btn-rounded btn-light btn-outline-secondary">{{ $item->value . ' ' . $item->unit->name_fa . ' ' . $item->food->name_fa }}</span>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                        <hr/>
                    @endforeach

                </div>
            </div>
        </div>


        <div class="col col-12 col-md-6 col-lg-6">
            <div class="card card-default">
                <div class="card-header">اطلاعات سوپر نیوترینت ها</div>
                <div class="card-body">

                    <div>کالری پیشنهاد شده: <span>{{ number_format($program->calorie) }}</span></div>
                    <hr/>

                    <div>پروتئین پیشنهاد شده: <span>{{ number_format($program->protein) }}</span></div>
                    <hr/>

                    <div>کربوهیدرات پیشنهاد شده: <span>{{ number_format($program->carbs) }}</span></div>
                    <hr/>

                    <div>چربی پیشنهاد شده: <span>{{ number_format($program->fat) }}</span></div>
                    <hr/>

                </div>
            </div>
        </div>


        <div class="col col-12 col-md-6 col-lg-6">
            <div class="card card-default">
                <div class="card-header">مکمل ها</div>
                <div class="card-body">

                    @foreach($program->supplements as $supplement)
                        <span
                            class="btn m-2 btn-rounded btn-light btn-outline-secondary">{{ !empty($supplement->value) ? ($supplement->value . ' ' . $supplement->unit->name_fa . ' ' . $supplement->supplement->name_fa) : $supplement->unit_text }}</span>
                    @endforeach

                </div>
            </div>
        </div>

    </div>
@endsection
@include('admin.theme.master')
