@php
    $type = isset($support) ? 'edit' : 'add';
@endphp

@if(isset($support))
@section('buttons')
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
            تنظیمات
        </button>
        <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated">

            <a class="dropdown-item" href="javascript:void(0);" onclick="deleteItem({{ $support->id }})">حذف</a>
        </div>
    </div>
@endsection
@endif

@section('container')
    <div class="card card-default">
        <div class="card-body">
            <form id="main">
                <div class="form-group">
                    <label>نام پشتیبان:</label>
                    <input class="form-control" type="text" name="title"
                           value="@if($type == 'edit'){{ $support->full_name }}@endif" placeholder=""
                           required>
                </div>

                <div class="form-group">
                    <label>آپلود عکس پروفایل:</label>
                    <br>
                    @if($type == 'edit')
                        <img src="{{ $support->photo->thumbnail->absolute_path }}" id="photo_path" width="60"
                             height="60">
                    @endif
                    <input name="photo" id="photo" type="file" accept="image/*"/>
                </div>

                <div class="row">

                    <div class="col-12 col-xs-6 col-sm-6">
                        <div class="form-group">
                            <label>آیدی تلگرام:</label>
                            <input class="form-control" type="text" name="telegram_id"
                                   value="@if($type == 'edit'){{ $support->telegram_id }}@endif" placeholder=""
                                   required>
                        </div>
                    </div>

                    <div class="col-12 col-xs-6 col-sm-6">
                        <div class="form-group">
                            <label>شماره واتساپ:</label>
                            <input class="form-control" type="text" name="whatsapp_number"
                                   value="@if($type == 'edit'){{ $support->whatsapp_number }}@endif" placeholder=""
                                   required>
                        </div>
                    </div>
                </div>

                <button class="mb-5 btn-lg btn btn-outline-success btn-block mt-2" type="submit">
                    ثبت
                </button>

            </form>
        </div>
    </div>

@endsection
@section('scripts')
    <script>

        $("form#main").on('submit', function (e) {
            e.preventDefault()

            let $fullName = $(this).find("input[name='title']").val();
            let $whatsappNumber = $(this).find("input[name='whatsapp_number']").val();
            let $telegramId = $(this).find("input[name='telegram_id']").val();
            let $photo = $(this).find("input[name='photo']")[0].files[0]
            let $picturePath = $(this).find("#photo_path");

            if ($picturePath.attr('src') === undefined && $photo === undefined) {
                alertify.error('لطفا نمایه عکس را آپلود کنید.');
                return;
            }

            let formData = new FormData()
            formData.append('_token', $("meta[name='csrf-token']").attr('content'))
            formData.append('full_name', $fullName)
            formData.append('whatsapp_number', $whatsappNumber)
            formData.append('telegram_id', $telegramId)
            formData.append('photo', $photo === undefined ? '' : $photo)

            if ($picturePath.attr('src') === undefined && $photo === undefined) {
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
                        if (response['support'] != null)
                            $picturePath.attr('src', response['support']['photo']['thumbnail']['absolute_path'] + '?' + (new Date()).getTime())

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
                    url: '{{ Route('admin_support_manage') }}',
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
                                window.location.href = '{{ Route('admin_support_manage') }}'
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
