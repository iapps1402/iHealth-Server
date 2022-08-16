@php
    $type = isset($application) ? 'edit' : 'add';
@endphp
@section('container')
    <div class="card card-default">
        <div class="card-body">
            <form id="main">

                <div class="form-group">
                    <label>نام فارسی:</label>
                    <input class="form-control" type="text" name="name_fa"
                           value="@if($type == 'edit'){{ $application->name_fa }}@else{{ old('name_fa') }}@endif"
                           placeholder=""
                           required>
                </div>

                <div class="form-group">
                    <label>نام انگلیسی:</label>
                    <input class="form-control" type="text" name="name_en"
                           value="@if($type == 'edit'){{ $application->name_en }}@else{{ old('name_en') }}@endif"
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
            let $nameFa = $(this).find("input[name='name_fa']").val();
            let $nameEn = $(this).find("input[name='name_en']").val();

            $.ajax({
                type: "post",
                url: '',
                dataType: "json",
                data: {
                    '_token': $("meta[name='csrf-token']").attr('content'),
                    'name_fa': $nameFa,
                    'name_en': $nameEn,
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
