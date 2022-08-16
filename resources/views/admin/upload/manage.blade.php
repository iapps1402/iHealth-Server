@section('container')
    <div class="card card-default">
        <div class="card-body">
            <form id="main">
                <input name="file" type="file" accept="image/*" required/>
                {{ csrf_field() }}
                <br><br>
                <button class="mb-5 btn-lg col-12 btn btn-outline-success" type="submit">آپلود
                    فایل
                </button>
            </form>
        </div>
    </div>

    <div id="table_main">
        @include('admin.upload.component.manage_table')
    </div>

@endsection
@section('scripts')
    <script>
        function addToClipboard(text) {
            window.prompt('Copy to clipboard: Ctrl+C, Enter', text);
        }

        $("form#main").on('submit', function (e) {
            e.preventDefault()
            let $file = $("input[name='file']")[0].files[0];

            let formData = new FormData()
            formData.append('_token', $("meta[name='csrf-token']").attr('content'))
            formData.append('file', $file)
            formData.append('action', 'upload')

            $.ajax({
                type: "post",
                url: '',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.success) {
                        alertify.success(response['message']);
                        $("#table_main").html(response['view'])
                    } else
                        alertify.error(response['message']);
                },
                error: function () {
                    alertify.error("خطا رخ داد.");
                }
            })
        })

        function confirmDelete(id) {
            alertify.confirm("در صورت تایید، این آیتم حذف خواهد شد.", function (ev) {
                ev.preventDefault();
                $.ajax({
                    type: "post",
                    url: '',
                    dataType: "json",
                    data: {
                        '_token': $("meta[name='csrf-token']").attr('content'),
                        'id': id,
                        'action': 'delete'
                    },
                    success: function (response) {
                        if (response['success']) {
                            alertify.success(response['message']);
                            $("#table_main").html(response['view'])
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
