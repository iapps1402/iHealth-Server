@section('buttons')
    <button class="btn btn-secondary" type="button" onclick="addItem()">افزودن مخلفات جدید</button>
@endsection
@section('modal')
    <div class="modal fade" id="myModal" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel"></h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="بستن">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="id">
                        <input class="form-control mb-2" name="name_fa" required type="text" placeholder="عنوان فارسی">
                        <hr />
                        <input class="form-control mb-2" name="name_en" required type="text" placeholder="عنوان انگلیسی">
                        <hr />
                        <input class="form-control mb-2" name="value" required type="text" placeholder="مقدار (گرم)">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="button" data-dismiss="modal">بستن</button>
                        <button class="btn btn-primary" type="button" onclick="requestAddItem()">تایید</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
        @include('admin.food.unit.material.component.manage_table')
    </div>

@endsection
@section('scripts')
    <script>
        function addItem() {
            $("#myModalLabel").html('افزودن مخلفات');
            $("#myModal").modal('toggle');
            $("form").trigger('reset');
            $("form input[name='id']").val('');
        }

        function editItem(item) {
            $("#myModalLabel").html('ویرایش مخلفات');
            $("#myModal").modal('toggle');
            $("form").trigger('reset');
            let $id = $("form input[name='id']");
            let $titleFa = $("form input[name='name_fa']");
            let $titleEn = $("form input[name='name_en']");
            let $value = $("form input[name='value']");
            $id.val(item['id']);
            $titleFa.val(item['name_fa']);
            $titleEn.val(item['name_en']);
            $value.val(item['value']);
        }

        function requestAddItem() {
            let $id = $("form input[name='id']");
            let $titleEn = $("form input[name='name_en']");
            let $titleFa = $("form input[name='name_fa']");
            let $value = $("form input[name='value']");
            let $data;
            if($id.val() === '')
                $data = {
                    '_token': $("meta[name='csrf-token']").attr('content'),
                    'action': 'add',
                    'name_en': $titleEn.val(),
                    'name_fa': $titleFa.val(),
                    'value': $value.val()
                };
            else
                $data = {
                    '_token': $("meta[name='csrf-token']").attr('content'),
                    'action': 'edit',
                    'id': $id.val(),
                    'name_en': $titleEn.val(),
                    'name_fa': $titleFa.val(),
                    'value': $value.val()
                };

            $.ajax({
                type: "post",
                url: '',
                dataType: "json",
                data: $data,
                success: function (data) {
                    if(data.success) {
                        alertify.success(data.message);
                        $("div#table_main").html(data['view'])
                        $("#myModal").modal('toggle');
                    }
                    else
                        alertify.error(data.message);
                },
                error: function () {
                    $("div.loading").hide();
                    alertify.error("خطایی سمت سرور رخ داد. لطفا این موضوع را گزارش کنید.");
                }
            });
        }

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
