@php
    $type = isset($version) ? 'edit' : 'add';
@endphp
@if($type == 'edit')
@section('buttons')
    <button class="btn btn-secondary" type="button"
            onclick="window.location.href='{{ route('admin_application_version_change_manage' , ['application_id' => $applicationId, 'version_id' => $version->id]) }}'">
        تغییرات
    </button>
@endsection
@endif
@section('container')
    <div class="card card-default">
        <div class="card-body">
            <form id="main">

                <div class="form-group">
                    <label>شماره ورژن:</label>
                    <input class="form-control" type="text" name="number"
                           value="@if($type == 'edit'){{ $version->number }}@else{{ old('number') }}@endif"
                           placeholder=""
                           required>
                </div>

                <div class="form-group">
                    <label>نام ورژن:</label>
                    <input class="form-control" type="text" name="name"
                           value="@if($type == 'edit'){{ $version->name }}@else{{ old('name') }}@endif"
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
            let $number = $(this).find("input[name='number']").val();
            let $name = $(this).find("input[name='name']").val();

            $.ajax({
                type: "post",
                url: '',
                dataType: "json",
                data: {
                    '_token': $("meta[name='csrf-token']").attr('content'),
                    'number': $number,
                    'name': $name,
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
