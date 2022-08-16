@php
$type = !isset($slider) ? 'add' : 'edit';
@endphp
@if(isset($slider))
@section('buttons')
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
            تنظیمات
        </button>
        <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated">

            <a class="dropdown-item" href="javascript:void(0);" onclick="deleteItem({{ $slider->id }})">حذف</a>
        </div>
    </div>
@endsection
@endif
@section('container')
    <div class="card card-default">
        <div class="card-body">
            <form method="post" id="main">
                <div class="form-group">
                    <label>لینک:</label>
                    <br>
                    <input class="form-control" type="text" name="url"
                           value="@if($type == 'edit'){{ $slider->url }}@endif">
                </div>

                <div class="form-group">
                    <label>انتخاب عکس:</label>
                    <br>
                    @if($type == 'edit')
                        <img src="{{ $slider->picture->thumbnail->absolute_path }}" id="picture_path" width="100"
                             height="60">
                    @endif
                    <input name="picture" type="file" accept="image/*" />
                </div>

                <button class="mb-5 btn-lg col-12 btn btn-outline-success" type="submit">ارسال
                </button>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $("form#main").on('submit', function (e) {
            e.preventDefault()

            let $url = $(this).find("input[name='url']").val();
            let $picture = $(this).find("input[name='picture']")[0].files[0]
            let $picturePath = $(this).find("#picture_path");

            if ($picturePath.attr('src') === undefined && $picture === undefined) {
                alertify.error('لطفا نمایه غذا را آپلود کنید.');
                return;
            }

            let formData = new FormData()
            formData.append('_token', $("meta[name='csrf-token']").attr('content'))
            formData.append('url', $url)
            formData.append('picture', $picture === undefined ? '' : $picture)

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
                        if (response['slider'] != null)
                            $picturePath.attr('src', response['slider']['picture']['thumbnail']['absolute_path'] + '?' + (new Date()).getTime())
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
                    url: '{{ Route('admin_slider_manage') }}',
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
                                window.location.href = '{{ Route('admin_slider_manage') }}'
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
