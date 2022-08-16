@php
    $type = isset($activity) ? 'edit' : 'add';
@endphp
@if($type == 'edit')
@section('buttons')
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
            تنظیمات
        </button>
        <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated">
            <a class="dropdown-item" href="javascript:void(0);"
               onclick="window.location.href='{{ route('admin_activity_add') }}'">افزودن فعالیت جدید</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="javascript:void(0);" onclick="deleteItem({{ $activity->id }})">حذف</a>
        </div>
    </div>
@endsection
@endif
@section('container')
    <div class="card card-default">
        <div class="card-body">
            <form id="main">
                <div class="form-group">
                    <label>نام فارسی:</label>
                    <input class="form-control" type="text" name="name_fa"
                           value="@if($type == 'edit'){{ $activity->name_fa }}@else{{ old('name_fa') }}@endif"
                           placeholder=""
                           required>
                </div>

                <div class="form-group">
                    <label>نام انگلیسی:</label>
                    <input class="form-control" type="text" name="name_en"
                           value="@if($type == 'edit'){{ $activity->name_en }}@else{{ old('name_en') }}@endif"
                           placeholder=""
                           required>
                </div>

                <div class="form-group">
                    <label>انتخاب آیکن:</label>
                    <select class="custom-select mb-3" name="icon" required>
                        <option disabled
                                value="" @if($type == 'add'){{ 'selected' }}@endif>انتخاب
                            کنید...
                        </option>

                        <option @if($type == 'edit' && $activity->icon == 'fa_volleyball_ball_solid'){{ 'selected' }}@endif value="fa_volleyball_ball_solid">والیبال</option>
                        <option @if($type == 'edit' && $activity->icon == 'fa_walking_solid'){{ 'selected' }}@endif value="fa_walking_solid">پیاده روی</option>
                        <option @if($type == 'edit' && $activity->icon == 'fa_running_solid'){{ 'selected' }}@endif value="fa_running_solid">دویدن</option>
                        <option @if($type == 'edit' && $activity->icon == 'fa_baseball_ball_solid'){{ 'selected' }}@endif value="fa_baseball_ball_solid">بیسبال</option>
                        <option @if($type == 'edit' && $activity->icon == 'fa_table_tennis_solid'){{ 'selected' }}@endif value="fa_table_tennis_solid">تنیس</option>
                        <option @if($type == 'edit' && $activity->icon == 'fa_golf_ball_solid'){{ 'selected' }}@endif value="fa_golf_ball_solid">گلف</option>
                        <option @if($type == 'edit' && $activity->icon == 'fa_futbol_solid'){{ 'selected' }}@endif value="fa_futbol_solid">فوتبال</option>
                        <option @if($type == 'edit' && $activity->icon == 'fa_basketball_ball_solid'){{ 'selected' }}@endif value="fa_basketball_ball_solid">بسکتبال</option>
                        <option @if($type == 'edit' && $activity->icon == 'fa_dumbbell_solid'){{ 'selected' }}@endif value="fa_dumbbell_solid">بدنسازی و تناسب اندام</option>
                        <option @if($type == 'edit' && $activity->icon == 'fa_bicycle_solid'){{ 'selected' }}@endif value="fa_bicycle_solid">دوچرخه سواری</option>
                        <option @if($type == 'edit' && $activity->icon == 'fa_swimming_pool_solid'){{ 'selected' }}@endif value="fa_swimming_pool_solid">شنا</option>
                        <option @if($type == 'edit' && $activity->icon == 'fa_bowling_ball_solid'){{ 'selected' }}@endif value="fa_bowling_ball_solid">بولینگ</option>
                        <option @if($type == 'edit' && $activity->icon == 'fa_skating_solid'){{ 'selected' }}@endif value="fa_skating_solid">اسکیت</option>
                        <option @if($type == 'edit' && $activity->icon == 'fa_skiing_solid'){{ 'selected' }}@endif value="fa_skating_solid">اسکی</option>
                        <option @if($type == 'edit' && $activity->icon == 'fa_snowboarding_solid'){{ 'selected' }}@endif value="fa_skating_solid">اسنوبورد</option>
                        <option @if($type == 'edit' && $activity->icon == 'fa_music_solid'){{ 'selected' }}@endif value="fa_music_solid">رقص</option>
                        <option @if($type == 'edit' && $activity->icon == 'fa_book_solid'){{ 'selected' }}@endif value="fa_book_solid">کتاب خواندن</option>
                        <option @if($type == 'edit' && $activity->icon == 'fa_bed_solid'){{ 'selected' }}@endif value="fa_bed_solid">خوابیدن</option>
                        <option @if($type == 'edit' && $activity->icon == 'fa_hiking_solid'){{ 'selected' }}@endif value="fa_hiking_solid">هاکی</option>
                        <option @if($type == 'edit' && $activity->icon == 'fa_quidditch_solid'){{ 'selected' }}@endif value="quidditch">کوییدیچ</option>
                        <option @if($type == 'edit' && $activity->icon == 'fa_horse_solid'){{ 'selected' }}@endif value="fa_horse_solid">اسب سواری</option>
                        <option @if($type == 'edit' && $activity->icon == 'fa_mitten_solid'){{ 'selected' }}@endif value="fa_mitten_solid">بوکس</option>
                        <option @if($type == 'edit' && $activity->icon == 'fa_horse_solid'){{ 'selected' }}@endif value="fa_horse_solid">جام قهرمانی</option>
                        <option @if($type == 'edit' && $activity->icon == 'fa_football_ball_solid'){{ 'selected' }}@endif value="fa_football_ball_solid">توپ فوتبال آمریکایی</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>MET:</label>
                    <input class="form-control" type="tel" name="met"
                           value="@if($type == 'edit'){{ $activity->met }}@else{{ old('met') }}@endif"
                           placeholder=""
                           required>
                </div>


                <div class="mt-2">
                    <button class="mb-5 col-12 btn-lg btn btn-outline-success" type="submit">ثبت
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        function deleteItem(id) {
            alertify.confirm("در صورت تایید، این آیتم حذف خواهد شد.", function (ev) {
                ev.preventDefault();
                $.ajax({
                    type: "post",
                    url: '{{ route('admin_activity_manage') }}',
                    dataType: "json",
                    data: {
                        '_token': $("meta[name='csrf-token']").attr('content'),
                        'id': id,
                        'action': 'delete'
                    },
                    success: function (response) {
                        if (response['success']) {
                            alertify.success(response['message']);
                            setTimeout(function () {
                                window.location.href = '{{ route('admin_activity_manage') }}'
                            }, 1500)
                        } else
                            alertify.error(response['message']);
                    },
                    error: function () {
                        alertify.error('خطا رخ داد.');
                    }
                })
            }, function (ev) {
                ev.preventDefault();
            });
        }

        $("form#main").on('submit', function (e) {
            e.preventDefault()

            let $nameFa = $(this).find("input[name='name_fa']").val();
            let $nameEn = $(this).find("input[name='name_en']").val();
            let $met = $(this).find("input[name='met']").val();
            let $icon = $(this).find("select[name='icon']").val();

            $.ajax({
                type: "post",
                url: '',
                data: {

                    '_token': $("meta[name='csrf-token']").attr('content'),
                    'name_fa': $nameFa,
                    'name_en': $nameEn,
                    'met': $met,
                    'icon': $icon
                },
                success: function (response) {
                    if (response.success) {
                        alertify.success(response['message']);

                        if (response['redirect_url'] != null) {
                            setTimeout(function () {
                                window.location.href = response['redirect_url']
                            }, 1500)
                        }
                    } else
                        alertify.error(response['message']);
                },
                error: function () {
                    alertify.error("خطا رخ داد.");
                }
            })
        })

        function deleteItem(id) {
            alertify.confirm("در صورت تایید، این آیتم حذف خواهد شد.", function (ev) {
                ev.preventDefault();
                $.ajax({
                    type: "post",
                    url: '{{ Route('admin_activity_manage') }}',
                    dataType: "json",
                    data: {
                        '_token': $("meta[name='csrf-token']").attr('content'),
                        'id': id,
                        'action': 'delete'
                    },
                    success: function (response) {
                        if (response['success']) {
                            alertify.success(response['message']);
                            setTimeout(function () {
                                window.location.href = '{{ Route('admin_activity_manage') }}'
                            }, 1500)
                        } else
                            alertify.error(response['message']);
                    },
                    error: function () {
                        alertify.error('خطا رخ داد.');
                    }
                })
            }, function (ev) {
                ev.preventDefault();
            });
        }
    </script>
@endsection
@include('admin.theme.master')
