@php
    $type = isset($food) ? 'edit' : 'add';
@endphp
@if($type == 'edit')
@section('buttons')
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
            تنظیمات
        </button>
        <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated">
            <a class="dropdown-item" href="{{ Route('admin_food_unit_manage', ['food_id' => $food->id]) }}">مدیریت
                واحدها</a>

            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="{{ Route('admin_cooking_ingredient_manage', ['food_id' => $food->id]) }}">مدیریت
                اجزا غذا</a>
            <a class="dropdown-item" href="{{ Route('admin_cooking_instruction_manage', ['food_id' => $food->id]) }}">مدیریت
                دستور پخت</a>
            <div class="dropdown-divider"></div>

            <a class="dropdown-item" href="javascript:void(0);" onclick="deleteItem({{ $food->id }})">حذف</a>
        </div>
    </div>
@endsection
@endif
@section('container')
    <div class="card card-default">
        <div class="card-body">
            <form id="main">
                {{ csrf_field() }}

                <input type="hidden" name="action" value="{{ $type }}">

                <div class="form-group">
                    <label>نام فارسی:</label>
                    <input class="form-control" type="text" name="name_fa"
                           value="@if($type == 'edit'){{ $food->name_fa }}@else{{ old('name_fa') }}@endif"
                           placeholder=""
                           required>
                </div>

                <div class="form-group">
                    <label>نام انگلیسی:</label>
                    <input class="form-control" type="text" name="name_en"
                           value="@if($type == 'edit'){{ $food->name_en }}@else{{ old('name_en') }}@endif"
                           placeholder=""
                           required>
                </div>

                <div class="form-group">
                    <label>انتخاب دسته بندی ها</label>
                    <div>
                        @foreach($categories as $category)
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input"
                                       name="categories"
                                       value="{{ $category->id }}"
                                       id="category{{ $category->id }}"
                                       @if($type == 'edit' && $food->categories->contains(function ($item, $key) use($category) { return $category->id == $item->id; })){{ 'checked' }}@endif
                                       data-parsley-multiple="groups">
                                <label class="custom-control-label"
                                       for="category{{ $category->id }}">{{ $category->name_fa }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label>نمایه غذا:</label>
                    <br>
                    @if($type == 'edit')
                        <img src="{{ $food->picture->thumbnail->absolute_path }}" id="picture_path" width="100"
                             height="60">
                    @endif
                    <input name="picture" id="picture" type="file" accept="image/*"/>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label>توضیحات فارسی(اختیاری):</label>
                            <textarea class="form-control text-left" type="text" name="description_fa"
                                      placeholder="">@if($type == 'edit'){{ $food->description_fa }}@else{{ old('description_fa') }}@endif</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label>توضیحات انگلیسی(اختیاری):</label>
                            <textarea dir="ltr" class="form-control text-left" type="text" name="description_en"
                                      placeholder="">@if($type == 'edit'){{ $food->description_en }}@else{{ old('description_en') }}@endif</textarea>
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label>بارکد:</label>
                    <input class="form-control" type="text" name="barcode"
                           value="@if($type == 'edit'){{ $food->barcode }}@else{{ old('barcode') }}@endif"
                           placeholder="">
                </div>


                <h3 class="mt-5 mb-3">تنظیمات دستور پخت و مواد لازم</h3>

                <div class="row">
                    <div class="col-12 col-lg-6 col-md-6">
                        <div class="form-group">
                            <label>مقدار پخت (فارسی):</label>
                            <input class="form-control" type="text" name="cooking_amount_fa"
                                   value="@if($type == 'edit'){{ $food->cooking->amount_fa }}@else{{ old('cooking_amount_fa') }}@endif"
                                   placeholder="">
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 col-md-6">
                        <div class="form-group">
                            <label>مقدار پخت (انگلیسی):</label>
                            <input class="form-control text-left" type="text" name="cooking_amount_en"
                                   dir="ltr"
                                   value="@if($type == 'edit'){{ $food->cooking->amount_en }}@else{{ old('cooking_amount_en') }}@endif"
                                   placeholder="">
                        </div>
                    </div>

                </div>


                <div class="row">
                    <div class="col-12 col-lg-6 col-md-6">
                        <div class="form-group">
                            <label>کالری مقدار پخت:</label>
                            <input class="form-control" type="text" name="cooking_calorie"
                                   value="@if($type == 'edit'){{ $food->cooking->calorie }}@else{{ old('cooking_calorie') }}@endif"
                                   placeholder="">
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 col-md-6">
                        <div class="form-group">
                            <label>زمان پخت (دقیقه):</label>
                            <input class="form-control" type="text" name="cooking_time"
                                   value="@if($type == 'edit'){{ $food->cooking->time }}@else{{ old('cooking_time') }}@endif"
                                   placeholder="">
                        </div>
                    </div>
                </div>

                <div class="mt-2">
                    <button class="mb-5 btn-lg col-12 btn btn-outline-success" type="submit">ارسال
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
                    url: '{{ Route('admin_food_manage') }}',
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
                                window.location.href = '{{ Route('admin_food_manage') }}'
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
            let nameFa = $(this).find("input[name='name_fa']").val();
            let $nameEn = $(this).find("input[name='name_en']").val();
            let $barcode = $(this).find("input[name='barcode']").val();
            let $descriptionFa = $(this).find("textarea[name='description_fa']").val();
            let $descriptionEn = $(this).find("textarea[name='description_en']").val();
            let $picture = $(this).find("input[name='picture']")[0].files[0]
            let $picturePath = $(this).find("#picture_path");
            let $categories = $(this).find("input[name='categories']:checked").map(function () {
                return $(this).val();
            }).get()

            let $cookingAmountFa = $(this).find("input[name='cooking_amount_fa']").val();
            let $cookingAmountEn = $(this).find("input[name='cooking_amount_en']").val();
            let $cookingTime = $(this).find("input[name='cooking_time']").val();
            let $cookingCalorie = $(this).find("input[name='cooking_calorie']").val();

            if ($picturePath.attr('src') === undefined && $picture === undefined) {
                alertify.error('لطفا نمایه غذا را آپلود کنید.');
                return;
            }

            let formData = new FormData()
            formData.append('_token', $("meta[name='csrf-token']").attr('content'))
            formData.append('name_fa', nameFa)
            formData.append('name_en', $nameEn)
            formData.append('barcode', $barcode)
            formData.append('description_fa', $descriptionFa)
            formData.append('description_en', $descriptionEn)
            formData.append('cooking_time', $cookingTime)
            formData.append('cooking_amount_fa', $cookingAmountFa)
            formData.append('cooking_amount_en', $cookingAmountEn)
            formData.append('cooking_calorie', $cookingCalorie)
            formData.append('categories', '[' + $categories + ']')
            formData.append('picture', $picture === undefined ? '' : $picture)

            $.ajax({
                type: "post",
                url: '',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.success) {
                        if (response['food'] != null)
                            $picturePath.attr('src', response['food']['picture']['thumbnail']['absolute_path'] + '?' + (new Date()).getTime())
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
