@section('buttons')
    <button class="btn btn-secondary" type="button"
            onclick="window.location.href='{{ route('admin_activity_add') }}'">افزودن فعالیت
    </button>
@endsection
@section('container')

    <div class="row">
        <div class="col-lg-8 col-md-6">

        </div>
        <div class="col-lg-4 col-md-6">
            <form class="search-bar p-3 float-le" method="get" action="">
                <div class="position-relative">
                    <input name="q" value="{{ Request()->q }}" type="text" class="form-control" placeholder="جستجو...">
                </div>
            </form>
        </div>
    </div>

    <div id="table_main">
        @include('admin.activity.components.manage_table')
    </div>
@endsection
@section('scripts')
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
