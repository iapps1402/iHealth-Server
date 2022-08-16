@php
    $type = isset($unit) ? 'edit' : 'add';
@endphp
@if(isset($unit))
@section('buttons')
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
            تنظیمات
        </button>
        <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated">
            <a class="dropdown-item"
               href="{{ Route('admin_food_unit_material_manage', ['food_id' => $food->id, 'unit_id' => $unit->id]) }}">مدیریت
                مخلفات</a>

            <a class="dropdown-item" href="javascript:void(0);" onclick="deleteItem({{ $unit->id }})">حذف</a>
        </div>
    </div>
@endsection
@endif
@section('container')
    <div class="card card-default">
        <div class="card-body">
            <form id="main">
                <div class="form-group">
                    <label>عنوان فارسی واحد:</label>
                    <input class="form-control" type="text" name="name_fa"
                           value="@if($type == 'edit'){{ $unit->name_fa }}@endif"
                           placeholder=""
                           required>
                </div>

                <div class="form-group">
                    <label>عنوان انگلیسی واحد:</label>
                    <input class="form-control" type="text" name="name_en"
                           value="@if($type == 'edit'){{ $unit->name_en }}@endif"
                           placeholder=""
                           required>
                </div>

                <div class="form-group">
                    <label>تعداد واحد :</label>
                    <input class="form-control" type="text" name="number"
                           value="@if($type == 'edit'){{ $unit->number }}@endif"
                           placeholder=""
                           required>
                </div>

                <div class="form-group">
                    <label>کربوهیدرات (گرم):</label>
                    <input class="form-control" type="tel" name="carbs"
                           value="@if($type == 'edit'){{ $unit->carbs }}@endif" placeholder=""
                           required>
                </div>

                <div class="form-group">
                    <label>پروتئین (گرم):</label>
                    <input class="form-control" type="tel" name="protein"
                           value="@if($type == 'edit'){{ $unit->protein }}@endif"
                           placeholder=""
                           required>
                </div>

                <div class="form-group">
                    <label>چربی (گرم):</label>
                    <input class="form-control" type="tel" name="fat"
                           value="@if($type == 'edit'){{ $unit->fat }}@endif" placeholder=""
                           required>
                </div>

                <div class="form-group">
                    <label>کالری:</label>
                    <input class="form-control" type="tel" name="calorie"
                           value="@if($type == 'edit'){{ $unit->calorie }}@endif" placeholder="">
                </div>

                <div class="form-group">
                    <label>فیبر (گرم):</label>
                    <input class="form-control" type="tel" name="fiber"
                           value="@if($type == 'edit'){{ $unit->fiber }}@endif" placeholder=""
                           required>
                </div>

                <div class="form-group">
                    <label>انتخاب آیکن:</label>
                    <select class="custom-select mb-3" name="icon" required>
                        <option disabled
                                value="" @if($type == 'add'){{ 'selected' }}@endif>انتخاب
                            کنید...
                        </option>
                        <option
                            value="fa_wine_glass_solid" @if(($type == 'edit' && $unit->icon == 'fa_wine_glass_solid')){{ 'selected' }}@endif>
                            لیوان
                        </option>
                        <option
                            value="fa_utensil_spoon_solid" @if(($type == 'edit' && $unit->icon == 'fa_utensil_spoon_solid')){{ 'selected' }}@endif>
                            قاشق
                        </option>
                        <option
                            value="fa_hamburger_solid" @if(($type == 'edit' && $unit->icon == 'fa_hamburger_solid')){{ 'selected' }}@endif>
                            همبرگر
                        </option>
                        <option
                            value="fa_pizza_slice_solid" @if(($type == 'edit' && $unit->icon == 'fa_pizza_slice_solid')){{ 'selected' }}@endif>
                            برش پیتزا
                        </option>
                        <option
                            value="fa_egg_solid" @if(($type == 'edit' && $unit->icon == 'fa_egg_solid')){{ 'selected' }}@endif>
                            تخم مرغ
                        </option>
                        <option
                            value="fa_cheese_solid" @if(($type == 'edit' && $unit->icon == 'fa_cheese_solid')){{ 'selected' }}@endif>
                            پنیر
                        </option>
                        <option
                            value="fa_bacon_solid" @if(($type == 'edit' && $unit->icon == 'fa_bacon_solid')){{ 'selected' }}@endif>
                            بیکن
                        </option>

                        <option
                            value="fa_bread_slice_solid" @if(($type == 'edit' && $unit->icon == 'fa_bread_slice_solid')){{ 'selected' }}@endif>
                            نان
                        </option>

                        <option
                            value="fa_ice_cream_solid" @if(($type == 'edit' && $unit->icon == 'fa_ice_cream_solid')){{ 'selected' }}@endif>
                            بستنی
                        </option>

                        <option
                            value="fa_cocktail_solid" @if(($type == 'edit' && $unit->icon == 'fa_cocktail_solid')){{ 'selected' }}@endif>
                            کوکتل
                        </option>

                        <option
                            value="fa_cheese_solid" @if(($type == 'edit' && $unit->icon == 'fa_cheese_solid')){{ 'selected' }}@endif>
                            پنیر
                        </option>

                        <option
                            value="fa_fish_solid" @if(($type == 'edit' && $unit->icon == 'fa_fish_solid')){{ 'selected' }}@endif>
                            ماهی
                        </option>

                        <option
                            value="fa_hotdog_solid" @if(($type == 'edit' && $unit->icon == 'fa_hotdog_solid')){{ 'selected' }}@endif>
                            هات داگ
                        </option>

                        <option
                            value="fa_cookie_solid" @if(($type == 'edit' && $unit->icon == 'fa_cookie_solid')){{ 'selected' }}@endif>
                            پختنی
                        </option>

                        <option
                            value="fa_utensil_spoon_solid" @if(($type == 'edit' && $unit->icon == 'fa_utensil_spoon_solid')){{ 'selected' }}@endif>
                            قاشق
                        </option>

                        <option
                            value="fa_utensils_solid" @if(($type == 'edit' && $unit->icon == 'fa_utensils_solid')){{ 'selected' }}@endif>
                            قاشق و چنگال
                        </option>
                    </select>
                </div>

                <button class="mb-5 col-12 btn-lg btn btn-outline-success mt-2" type="submit">ثبت</button>
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
                    url: '{{ Route('admin_food_unit_manage', ['food_id' => $food->id]) }}',
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
                                window.location.href = '{{ Route('admin_food_unit_manage', ['food_id' => $food->id]) }}'
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
            let $titleFa = $(this).find("input[name='name_fa']").val();
            let $titleEn = $(this).find("input[name='name_en']").val();
            let $carbs = $(this).find("input[name='carbs']").val();
            let $number = $(this).find("input[name='number']").val();
            let $fat = $(this).find("input[name='fat']").val();
            let $fiber = $(this).find("input[name='fiber']").val();
            let $protein = $(this).find("input[name='protein']").val();
            let $calorie = $(this).find("input[name='calorie']").val();
            let $icon = $(this).find("select[name='icon']").val();

            $.ajax({
                type: "post",
                url: '',
                data: {
                    '_token': $("meta[name='csrf-token']").attr('content'),
                    'name_fa': $titleFa,
                    'name_en': $titleEn,
                    'carbs': $carbs,
                    'number': $number,
                    'fat': $fat,
                    'fiber': $fiber,
                    'protein': $protein,
                    'calorie': $calorie,
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
    </script>
@endsection
@include('admin.theme.master')
