@section('buttons')
    <button class="btn btn-secondary" type="button" onclick="window.location.href='{{ route('admin_application_version_change_add', ['application_id' => $applicationId, 'version_id' => $versionId]) }}'">افزودن تغییر جدید</button>
@endsection
@section('container')

    <div id="table_main">
        @include('admin.application.version.change.components.manage_table')
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
    </script>
@endsection
@include('admin.theme.master')
