@section('buttons')
    <button class="btn btn-secondary" type="button" onclick="window.location.href='{{ route('admin_application_version_add', ['application_id' => $applicationId]) }}'">افزودن ورژن جدید</button>
@endsection
@section('container')

    <div id="table_main">
        @include('admin.application.version.components.manage_table')
    </div>

    <script>
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
                        }
                        else
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

        function currentVersion(id) {
            alertify.confirm("در صورت تایید، این نسخه بعنوان نسخه فعلی انتخاب خواهد شد.", function (ev) {
                ev.preventDefault();
                $.ajax({
                    type: "post",
                    url: '',
                    dataType: "json",
                    data: {
                        '_token': $("meta[name='csrf-token']").attr('content'),
                        'id': id,
                        'action': 'current_version'
                    },
                    success: function (response) {
                        if (response['success']) {
                            alertify.success(response['message']);
                            $("#table_main").html(response['view'])
                        }
                        else
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

        function minVersion(id) {
            alertify.confirm("در صورت تایید، این نسخه بعنوان کمترین نسخه انتخاب خواهد شد.", function (ev) {
                ev.preventDefault();
                $.ajax({
                    type: "post",
                    url: '',
                    dataType: "json",
                    data: {
                        '_token': $("meta[name='csrf-token']").attr('content'),
                        'id': id,
                        'action': 'min_version'
                    },
                    success: function (response) {
                        if (response['success']) {
                            alertify.success(response['message']);
                            $("#table_main").html(response['view'])
                        }
                        else
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
