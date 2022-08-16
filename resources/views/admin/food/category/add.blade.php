@php
    $type = !isset($category) ? 'add' : 'edit';
@endphp
@if(isset($category))
@section('buttons')
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
            تنظیمات
        </button>
        <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated">
            <a class="dropdown-item" href="javascript:void(0);" onclick="window.location.href='{{ route('admin_food_category_add') }}'">افزودن دسته بندی جدید</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="javascript:void(0);" onclick="deleteItem({{ $category->id }})">حذف</a>
        </div>
    </div>
@endsection
@endif
@section('container')
    <div class="card card-default">
        <div class="card-body">
            <form id="main">
                {{ csrf_field() }}
                <div class="form-group">
                    <label>نام فارسی:</label>
                    <br>
                    <input class="form-control" type="text" name="name_fa"
                           value="@if($type == 'edit'){{ $category->name_fa }}@else{{ old('name_fa') }}@endif">
                </div>

                <div class="form-group">
                    <label>نام انگلیسی:</label>
                    <br>
                    <input class="form-control" type="text" name="name_en"
                           value="@if($type == 'edit'){{ $category->name_en }}@else{{ old('name_en') }}@endif">
                </div>

                <div class="form-group">
                    <label>وضعیت نمایش در دسته بندی های اپلیکیشن:</label>
                    <select class="custom-select mb-3" name="in_app" required>
                        <option
                            value="1" @if((isset($category) && $category->in_app) || !isset($category)){{ 'selected' }}@endif>
                            نمایش
                        </option>
                        <option
                            value="0" @if(isset($category) && !$category->in_app){{ 'selected' }}@endif>
                            عدم نمایش
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label>تصویر دسته بندی:</label>
                    <br>
                    @if($type == 'edit')
                        <img src="{{ $category->picture->thumbnail->absolute_path }}" id="picture_path" width="100"
                             height="60">
                    @endif
                    <input name="picture" type="file" accept="image/*"/>
                </div>

                <button class="mb-5 btn-lg col-12 btn btn-outline-success" type="submit">ارسال</button>
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
                    url: '{{ Route('admin_food_category_manage') }}',
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
                                window.location.href = '{{ Route('admin_food_category_manage') }}'
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
            let $nameFa = $("form#main input[name='name_fa']").val();
            let $nameEn = $("form#main input[name='name_en']").val();
            let $inApp = $("form#main select[name='in_app']").val();
            let $picture = $("form#main input[name='picture']")[0].files[0]
            let $picturePath = $("form#main #picture_path");

            if ($picturePath.attr('src') === undefined && $picture === undefined) {
                alertify.error('لطفا تصویر دسته بندی را انتخاب کنید.');
                return;
            }

            let formData = new FormData()
            formData.append('_token', $("meta[name='csrf-token']").attr('content'))
            formData.append('name_fa', $nameFa)
            formData.append('name_en', $nameEn)
            formData.append('in_app', $inApp)
            formData.append('picture', $picture === undefined ? '' : $picture)

            $.ajax({
                type: "post",
                url: '',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.success) {
                        if (response['category'] != null)
                            $picturePath.attr('src', response['category']['picture']['thumbnail']['absolute_path'] + '?' + (new Date()).getTime())
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
