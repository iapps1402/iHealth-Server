@section('container')
    <div class="card card-default">
        <div class="card-body">
            <form id="main">

                <div class="form-group">
                    <label>انتخاب غذا:</label>
                    <select class="custom-select mb-3" name="food_id" id="food_id" required>
                        <option disabled
                                value="" selected>انتخاب
                            کنید...
                        </option>
                        @foreach($foods as $food)
                            <option
                                value="{{ $food->id }}">{{ $food->name_fa }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>انتخاب واحد:</label>
                    <select class="custom-select mb-3" name="unit_id" id="unit_id" required>
                        <option disabled selected>انتخاب
                            کنید...
                        </option>
                    </select>
                </div>

                <button class="mb-5 btn-lg col-12 btn btn-outline-success" type="submit">ارسال
                </button>
            </form>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        $('#food_id').on('change', function () {
            $.ajax({
                type: "post",
                url: '{{ Route('admin_food_suggestion_manage') }}',
                dataType: "json",
                data: {
                    '_token': $("meta[name='csrf-token']").attr('content'),
                    'id': this.value,
                    'action': 'units'
                },
                success: function (data) {
                    if (data.success) {
                        let $html = '';

                        for (let i = 0; i < data.units.length; i++) {
                            $html += '<option value="' + data.units[i].id + '">' + data.units[i].name_fa + '</option>';
                        }

                        $('#unit_id').html($html);
                    } else
                        alertify.error(data.message);
                },
                error: function (xhr, status, error) {
                    $("div.loading").hide();
                    console.log(error);
                    alertify.error("خطایی سمت سرور رخ داد. لطفا این موضوع را گزارش کنید.");
                }
            });
        });

        $("form#main").on('submit', function (event) {
            event.preventDefault()
            let $foodId = $(this).find("select[name='food_id']").val()
            let $unitId = $(this).find("select[name='unit_id']").val()

            if ($foodId === '') {
                alertify.error('لطفا غذا را انتخاب کنید.')
                return
            }

            if ($unitId === '') {
                alertify.error('لطفا واحد غذا را انتخاب کنید.')
                return
            }

            $.ajax({
                type: "post",
                url: '',
                dataType: "json",
                data: {
                    '_token': $("meta[name='csrf-token']").attr('content'),
                    'food_id': $foodId,
                    'unit_id': $unitId,
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
