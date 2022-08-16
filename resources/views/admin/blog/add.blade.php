@php
    $type = isset($post) ? 'edit' : 'add';
@endphp

@if(isset($post))
@section('buttons')
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
            تنظیمات
        </button>
        <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated">

            <a class="dropdown-item" href="javascript:void(0);" onclick="deleteItem({{ $post->id }})">حذف</a>
        </div>
    </div>
@endsection
@endif

@section('container')
    <div class="alert alert-warning"
         @if($type == 'add' || ($type == 'edit' && $post->status == 'published')){!! 'style="display:none;"' !!}@endif role="alert"
         id="draft">
        این پست پیش نویس است و هنوز در وب سایت منتشر نشده است.
    </div>

    <div class="card card-default">
        <div class="card-body">
            <form id="main">
                <input type="hidden" value="@if($type == 'edit'){{ $post->id }}@endif" name="id">
                <input type="hidden" name="action" value="{{ $type }}">

                <div class="form-group">
                    <label>عنوان مطلب:</label>
                    <input class="form-control" type="text" name="title"
                           value="@if($type == 'edit'){{ $post->title }}@endif" placeholder=""
                           required>
                </div>

                <div class="form-group">
                    <label>انتخاب دسته بندی:</label>
                    <select class="custom-select mb-3" name="category_id" required>
                        <option disabled
                                value="" @if($type == 'add'){{ 'selected' }}@endif>انتخاب
                            کنید...
                        </option>
                        @foreach($categories as $category)
                            <option
                                value="{{ $category->id }}" @if(($type == 'edit' && $post->category_id == $category->id)){{ 'selected' }}@endif>{{ $category->name_fa }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>نمایه مقاله:</label>
                    <br>
                    @if($type == 'edit')
                        <img src="{{ $post->picture->thumbnail->absolute_path }}" id="picture_path" width="100"
                             height="60">
                    @endif
                    <input name="picture" id="picture" type="file" accept="image/*"/>
                </div>

                <div class="form-group">
                    <label>انتخاب وضعیت:</label>
                    <select class="custom-select mb-3" name="status" required>
                        <option
                            value="published" @if((isset($post) && $post->status == 'published') || !isset($post)){{ 'selected' }}@endif>
                            منتشر شده
                        </option>
                        <option
                            value="draft" @if(isset($post) && $post->status == 'draft'){{ 'selected' }}@endif>
                            پیش نویس
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label>زبان مقاله:</label>
                    <select class="custom-select mb-3" name="language" required>
                        <option
                            value="fa" @if(($type == 'edit' && $post->language == 'fa')){{ 'selected' }}@endif>
                            فارسی
                        </option>
                        <option
                            value="en" @if(($type == 'edit' && $post->language == 'en')){{ 'selected' }}@endif>
                            انگلیسی
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label>خلاصه مقاله:</label>
                    <textarea class="form-control" type="text" name="summary" placeholder=""
                              required>@if($type == 'edit'){{ $post->summary }}@endif</textarea>
                </div>

                <label>متن مقاله:</label>
                <br>
                <textarea class="form-control" id="editor" dir="rtl" required name="editor" cols="50"
                          rows="10">@if($type == 'edit'){{ $post->text }}@endif</textarea>
                <script>
                    CKEDITOR.replace('editor');
                </script>


                <button class="mb-5 btn-lg btn btn-outline-success btn-block mt-2" type="submit">
                    ارسال مقاله
                </button>

            </form>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        $("form#main").on('submit', function (e) {
            e.preventDefault()

            let $title = $(this).find("input[name='title']").val();
            let $category = $(this).find("select[name='category_id']").val();
            let $status = $(this).find("select[name='status']").val();
            let $language = $(this).find("select[name='language']").val();
            let $picture = $(this).find("input[name='picture']")[0].files[0]
            let $picturePath = $(this).find("#picture_path");
            let $summary = $(this).find("textarea[name='summary']").val();
            let $text = $(this).find("input[name='text']");

            if ($picturePath.attr('src') === undefined && $picture === undefined) {
                alertify.error('لطفا نمایه غذا را آپلود کنید.');
                return;
            }

            let formData = new FormData()
            formData.append('_token', $("meta[name='csrf-token']").attr('content'))
            formData.append('title', $title)
            formData.append('summary', $summary)
            formData.append('category_id', $category)
            formData.append('text', $text)
            formData.append('language', $language)
            formData.append('status', $status)
            formData.append('picture', $picture === undefined ? '' : $picture)
            formData.append('text', CKEDITOR.instances.editor.getData())

            if ($picturePath.attr('src') === undefined && $picture === undefined) {
                alertify.error('لطفا نمایه غذا را آپلود کنید.');
                return;
            }

            $.ajax({
                type: "post",
                url: '',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.success) {
                        if (response['post'] != null)
                            $picturePath.attr('src', response['post']['picture']['thumbnail']['absolute_path'] + '?' + (new Date()).getTime())

                        if (response['post']['status'] === 'published')
                            $("div#draft").hide()
                        else
                            $("div#draft").show()

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
                    url: '{{ Route('admin_blog_post_manage') }}',
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
                                window.location.href = '{{ Route('admin_blog_post_manage') }}'
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
