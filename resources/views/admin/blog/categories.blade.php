@section('buttons')
    <button class="btn btn-secondary" type="button" onclick="addItem()">افزودن دسته بندی</button>
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
                        <input type="hidden" name="parent_id" value="{{ $parentId == 'root' ? '' : $parentId }}">
                        <input class="form-control mb-2" name="name_fa" required type="text" placeholder="عنوان فارسی">
                        <hr />
                        <input class="form-control mb-2" name="name_en" required type="text" placeholder="عنوان انگلیسی">
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
       @include('admin.blog.components.categories_table')
   </div>
@endsection
@section('scripts')
    <script>
        function addItem() {
            $("#myModalLabel").html('افزودن دسته بندی');
            $("#myModal").modal('toggle');
            $("form").trigger('reset');
            $("form input[name='id']").val('');
        }

        function editItem(item) {
            $("#myModalLabel").html('ویرایش دسته بندی');
            $("#myModal").modal('toggle');
            $("form").trigger('reset');
            let $id = $("form input[name='id']");
            let $titleFa = $("form input[name='name_fa']");
            let $titleEn = $("form input[name='name_en']");
            $id.val(item['id']);
            $titleFa.val(item['name_fa']);
            $titleEn.val(item['name_en']);
        }

        function requestAddItem() {
            let $id = $("form input[name='id']");
            let $titleEn = $("form input[name='name_en']");
            let $titleFa = $("form input[name='name_fa']");
            let $parentId = $("form input[name='parent_id']");
            let $data;
            if($id.val() === '')
                $data = {
                    '_token': $("meta[name='csrf-token']").attr('content'),
                    'action': 'add',
                    'name_en': $titleEn.val(),
                    'name_fa': $titleFa.val(),
                    'category_id': $parentId.val()
                };
            else
                $data = {
                    '_token': $("meta[name='csrf-token']").attr('content'),
                    'action': 'edit',
                    'id': $id.val(),
                    'name_en': $titleEn.val(),
                    'name_fa': $titleFa.val(),
                    'category_id': $parentId.val()
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
