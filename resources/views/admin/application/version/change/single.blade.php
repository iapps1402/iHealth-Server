@php
    $type = isset($change) ? 'edit' : 'add';
@endphp
@section('container')
    <div class="card card-default">
        <div class="card-body">
            <form id="main">

                <div class="form-group">
                    <label>توضیح فارسی:</label>
                    <input class="form-control" type="text" name="text_fa"
                           value="@if($type == 'edit'){{ $change->text_fa }}@else{{ old('text_fa') }}@endif"
                           placeholder=""
                           required>
                </div>

                <div class="form-group">
                    <label>توضیح انگلیسی:</label>
                    <input class="form-control" type="text" name="text_en"
                           value="@if($type == 'edit'){{ $change->text_en }}@else{{ old('text_en') }}@endif"
                           placeholder=""
                           required>
                </div>

                <button class="mb-5 btn-lg col-12 btn btn-outline-success" type="submit">ارسال
                </button>
            </form>
        </div>
    </div>

@endsection
@section('scripts')
    <script>

        $("form#main").on('submit', function (event) {
            event.preventDefault()
            let $textFa = $(this).find("input[name='text_fa']").val();
            let $textEn = $(this).find("input[name='text_en']").val();

            $.ajax({
                type: "post",
                url: '',
                dataType: "json",
                data: {
                    '_token': $("meta[name='csrf-token']").attr('content'),
                    'text_fa': $textFa,
                    'text_en': $textEn,
                },
                success: function (response) {
                    if (response.success) {
                        alertify.success(response['message'])
                        if (response['redirect_url'] != null) {
                            setTimeout(function () {
                                window.location.href = response['redirect_url']
                            }, 1500)
                        }
                    } else
                        alertify.error(response['message']);
                },
                error: function () {
                    alertify.error("خطایی سمت سرور رخ داد. لطفا این موضوع را گزارش کنید.");
                }
            });
        })
    </script>
@endsection
@include('admin.theme.master')
