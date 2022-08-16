@section('buttons')
    <button class="btn btn-secondary" type="button" onclick="addItem()">افزودن عنصر</button>
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
                        <input class="form-control mb-2" name="name_fa" required type="text" placeholder="نام فارسی">
                        <hr />
                        <input class="form-control mb-2" name="value_fa" required type="text" placeholder="مقدار فارسی">
                        <hr />
                        <input class="form-control mb-2 text-left" name="name_en" dir="ltr" required type="text" placeholder="نام انگلیسی">
                        <hr />
                        <input class="form-control mb-2 text-left" name="value_en" dir="ltr" required type="text" placeholder="مقدار انگلیسی">
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
   <div id="table_main">
       @include('admin.food.cooking.ingredient.components.manage_table')
   </div>
@endsection
@section('scripts')
    <script>
        function addItem() {
            $("#myModalLabel").html('افزودن عنصر جدید');
            $("#myModal").modal('toggle');
            $("form").trigger('reset');
            $("form input[name='id']").val('');
        }

        function editItem(item) {
            $("#myModalLabel").html('ویرایش عنصر');
            $("#myModal").modal('toggle');
            $("form").trigger('reset');
            let $id = $("form input[name='id']");
            let $nameFa = $("form input[name='name_fa']");
            let $valueFa = $("form input[name='value_fa']");
            let $nameEn = $("form input[name='name_en']");
            let $valueEn = $("form input[name='value_en']");
            $id.val(item['id']);
            $nameFa.val(item['name_fa']);
            $valueFa.val(item['value_fa']);
            $nameEn.val(item['name_en']);
            $valueEn.val(item['value_en']);
        }

        function requestAddItem() {
            let $id = $("form input[name='id']");
            let $nameFa = $("form input[name='name_fa']");
            let $valueFa = $("form input[name='value_fa']");
            let $nameEn = $("form input[name='name_en']");
            let $valueEn = $("form input[name='value_en']");
            let $data;
            if($id.val() === '')
                $data = {
                    '_token': $("meta[name='csrf-token']").attr('content'),
                    'action': 'add',
                    'name_fa': $nameFa.val(),
                    'value_fa': $valueFa.val(),
                    'name_en': $nameEn.val(),
                    'value_en': $valueEn.val()
                };
            else
                $data = {
                    '_token': $("meta[name='csrf-token']").attr('content'),
                    'action': 'edit',
                    'id': $id.val(),
                    'name_fa': $nameFa.val(),
                    'value_fa': $valueFa.val(),
                    'name_en': $nameEn.val(),
                    'value_en': $valueEn.val()
                };

            $.ajax({
                type: "post",
                url: '',
                dataType: "json",
                data: $data,
                success: function (response) {
                    if(response.success) {
                        alertify.success(response['message']);
                        $("#table_main").html(response['view'])
                        $("#myModal").modal('toggle');
                    }
                    else
                        alertify.error(response['message']);
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
