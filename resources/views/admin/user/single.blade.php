@php
    $type = isset($user) ? 'edit' : 'add';
@endphp

@section('container')
    <form id="main">
        <div class="card card-default">
            <div class="card-body">
                <div class="row">
                    <div class="col col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>نام <span class="text-danger">*</span> </label>
                            <input class="form-control" type="text" name="first_name"
                                   value="@if($type == 'edit' && $user->first_name != null){{ $user->first_name }}@endif"
                                   required>
                        </div>
                    </div>

                    <div class="col col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>نام خانوادگی <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="last_name"
                                   value="@if($type == 'edit' && $user->last_name != null){{ $user->last_name }}@endif"
                                   required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>شماره تماس</label>
                            <input class="form-control" type="tel" name="phone_number"
                                   value="@if($type == 'edit' && $user->phone_number != null){{ $user->phone_number }}@endif">
                        </div>
                    </div>

                    <div class="col col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>قد (واحد: سانتی متر) <span class="text-danger">*</span></label>
                            <input class="form-control" type="number" name="height"
                                   value="@if($type == 'edit' && $user->height != null){{ $user->height }}@endif"
                                   required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>جنسیت <span class="text-danger">*</span></label>
                            <select class="form-control" name="gender" required>
                                <option
                                    value="male" @if($type == 'edit' && $user->gender == 'male'){{ 'selected' }}@endif>
                                    مرد
                                </option>
                                <option
                                    value="female" @if($type == 'edit' && $user->gender == 'female'){{ 'selected' }}@endif>
                                    زن
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>تاریخ تولد <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="birth_date"
                                   data-jdp
                                   value="@if($type == 'edit' && $user->birth_date != null){{ \Morilog\Jalali\Jalalian::fromDateTime($user->birth_date)->format('%Y/%m/%d') }}@endif"
                                   required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>وزن (واحد: کیلوگرم) <span class="text-danger">*</span></label>
                            <input class="form-control" type="number" name="weight"
                                   value="@if($type == 'edit' && $user->weight != null){{ $user->weight }}@endif"
                                   required>
                        </div>
                    </div>

                    <div class="col col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>فعالیت <span class="text-danger">*</span></label>
                            <select class="form-control" name="activity" required>
                                <option
                                    data-value="1.1"
                                    value="no_physical_activity" @if($type == 'edit' && $user->activity == 'no_physical_activity'){{ 'selected' }}@endif>
                                    بدون تحرک
                                </option>
                                <option
                                    data-value="1.3"
                                    value="sedentary" @if($type == 'edit' && $user->activity == 'sedentary'){{ 'selected' }}@endif>
                                    کم تحرک
                                </option>
                                <option
                                    data-value="1.5"
                                    value="somehow_active" @if($type == 'edit' && $user->activity == 'somehow_active'){{ 'selected' }}@endif>
                                    به نحوی فعال
                                </option>
                                <option
                                    data-value="1.7"
                                    value="active" @if($type == 'edit' && $user->activity == 'active'){{ 'selected' }}@endif>
                                    فعال
                                </option>
                                <option
                                    data-value="1.9"
                                    value="very_active" @if($type == 'edit' && $user->activity == 'very_active'){{ 'selected' }}@endif>
                                    بیش فعال
                                </option>
                            </select>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>ایمیل:</label>
                            <input class="form-control" type="email" name="email"
                                   value="@if($type == 'edit' && $user->email != null){{ $user->email }}@endif">
                        </div>
                    </div>

                    <div class="col col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>تاریخ اعتبار دوره برنامه تغذیه:</label>
                            <input class="form-control" type="text" name="diet_program_expires_at"
                                   data-jdp
                                   value="@if($type == 'edit' && $user->diet_program_expires_at != null){{ \Morilog\Jalali\Jalalian::fromDateTime($user->diet_program_expires_at)->format('%Y/%m/%d') }}@endif">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>ضریب کاهش یا افزایش:</label>
                            <input class="form-control" type="tel" name="decrease_or_increase_coefficient"
                                   value="@if($type == 'edit' && $user->decrease_or_increase_coefficient != null){{ $user->decrease_or_increase_coefficient }}@else{{ '0' }}@endif">
                        </div>
                    </div>

                    <div class="col col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>وضعیت:</label>
                            <input class="form-control" type="tel" name="status" readonly>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card card-default">
                    <div class="card-body">
                        <h4 class="mt-0 header-title mb-4">انتخاب مقدار درشت مغذی ها</h4>

                        <div class="row mt-4">
                            <div class="col-2 col-sm-3 col-lg-2">
                                <div class="form-group">
                                    <label>کالری: (<span id="final_calorie"></span> )</label>
                                </div>
                            </div>

                            <div class="col-sm-2 col-lg-3">
                                <div class="form-group">
                                    <input type="text" id="calorie_edit_text" class="form-control"/>
                                </div>
                            </div>

                            <div class="col-7">
                                <input type="text" id="final_calorie_seekbar">
                            </div>

                        </div>


                        <div class="row" style="margin-top: 15px">
                            <div class="col-5">
                                <div class="form-group">
                                    <label>پروتئین: (<span style="font-weight: bold" id="final_protein"></span>
                                        )</label>
                                </div>
                            </div>

                            <div class="col-7">
                                <input type="text" id="final_protein_seekbar">
                            </div>

                        </div>


                        <div class="row" style="margin-top: 15px">
                            <div class="col-5">
                                <div class="form-group">
                                    <label>کربوهیدرات: (<span style="font-weight: bold" id="final_carbs"></span>
                                        )</label>
                                </div>
                            </div>

                            <div class="col-7">
                                <input type="text" id="final_carbs_seekbar">
                            </div>

                        </div>


                        <div class="row" style="margin-top: 15px">
                            <div class="col-5">
                                <div class="form-group">
                                    <label>چربی: (<span style="font-weight: bold" id="final_fat"></span> )</label>
                                </div>
                            </div>

                            <div class="col-7">
                                <input type="text" id="final_fat_seekbar">
                            </div>

                        </div>


                    </div>
                </div>
            </div>
        </div>

        <button class="mb-5 btn-lg btn btn-outline-success btn-block mt-2" type="submit">
            ثبت اطلاعات
        </button>

    </form>

    <div class="pb-4"></div>


@endsection
@section('scripts')
    <script>

        $(document).ready(function () {
            jalaliDatepicker.startWatch();
        })

        function getDateInGregorian(value) {
            if (value === "")
                return ''

            let splitDate = value.split('/')

            let jalali = jalaali.toGregorian(parseInt(splitDate[0]), parseInt(splitDate[1]), parseInt(splitDate[2]))

            return jalali.gy + '-' + jalali.gm + '-' + jalali.gd
        }

        $("form#main").on('submit', function (e) {
            e.preventDefault()
            let $dietProgramExpiresAt = $("form#main input[name='diet_program_expires_at']").val();
            let $birthDate = $("form#main input[name='birth_date']").val();
            let $firstname = $("form#main input[name='first_name']").val();
            let $lastname = $("form#main input[name='last_name']").val();
            let $phoneNumber = $("form#main input[name='phone_number']").val();
            let $height = $("form#main input[name='height']").val();
            let $gender = $("form#main select[name='gender']").val();
            let $weight = $("form#main input[name='weight']").val();
            let $activity = $("form#main select[name='activity']").val();
            let $email = $("form#main input[name='email']").val();

            if (!$phoneNumber.startsWith('9')) {
                alertify.error('شماره تلفن با 9 آغاز می شود.')
                return
            }

            let formData = new FormData()
            formData.append('_token', $("meta[name='csrf-token']").attr('content'))
            formData.append('birth_date', getDateInGregorian($birthDate))
            formData.append('first_name', $firstname)
            formData.append('last_name', $lastname)
            formData.append('phone_number', $phoneNumber)
            formData.append('height', $height)
            formData.append('gender', $gender)
            formData.append('weight', $weight)
            formData.append('activity', $activity)
            formData.append('email', $email)
            formData.append('decrease_or_increase_coefficient', $inputDecreaseOfIncreaseCoefficient.val())
            formData.append('calorie_ratio', $finalCalorieSeekbar.result.from)
            formData.append('protein_ratio', $finalProteinSeekbar.result.from)
            formData.append('fat_ratio', $finalFatSeekbar.result.from)
            formData.append('diet_program_expires_at', getDateInGregorian($dietProgramExpiresAt))

            $.ajax({
                type: "post",
                url: '',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.success) {
                        alertify.success(response['message']);

                        if (response['redirect_url'] != null) {
                            setTimeout(function () {
                                window.location.href = response['redirect_url']
                            }, 1500)
                        }
                    } else
                        alertify.error(response['message']);
                },
                error: function () {
                    alertify.error("خطا رخ داد.");
                }
            })
        })

        let $inputDecreaseOfIncreaseCoefficient = $("input[name='decrease_or_increase_coefficient']")
        let $calorieEditText = $("#calorie_edit_text")
        let $status = $("input[name='status']")

        let $finalCalorie = $("span#final_calorie")
        let $finalProtein = $("span#final_protein")
        let $finalCarbs = $("span#final_carbs")
        let $finalFat = $("span#final_fat")

        function isCompleted() {
            let $birthDate = $("form#main input[name='birth_date']").val()
            let $height = $("form#main input[name='height']").val()
            let $weight = $("form#main input[name='weight']").val()
            return $birthDate !== "" && $height !== "" && $weight !== "" &&!isNaN($height) && !isNaN($weight)
        }

        $("form#main input[name='weight']").on('change', function () {
            if(isCompleted())
                $inputDecreaseOfIncreaseCoefficient.trigger("change")
        });

        $("form#main input[name='height']").on('change', function () {
            if(isCompleted())
                $inputDecreaseOfIncreaseCoefficient.trigger("change")
        });

        $("form#main input[name='birth_date']").on('change', function () {
            if(isCompleted())
                $inputDecreaseOfIncreaseCoefficient.trigger("change")
        });

        $("form#main select[name='activity']").on('change', function () {
            if(isCompleted())
                $inputDecreaseOfIncreaseCoefficient.trigger("change")
        });

        $("form#main select[name='gender']").on('change', function () {
            if(isCompleted())
                $inputDecreaseOfIncreaseCoefficient.trigger("change")
        });


        function decreaseOrIncreaseCoefficient() {
            return parseFloat($inputDecreaseOfIncreaseCoefficient.val())
        }

        function getBMR() {
            let gender = $("form#main select[name='activity']").val()
            let height = $("form#main input[name='height']").val()
            let weight = $("form#main input[name='weight']").val()
            let birthDate = getDateInGregorian($("form#main input[name='birth_date']").val())

            let date1 = new Date(birthDate);
            let date2 = Date.now();
            let age = parseInt((date2 - date1) / (1000 * 60 * 60 * 24), 10) / 365;

            if (gender === 'male')
                return 88.362 + (weight * 13.397) + (height * 4.799) - (age * 5.677);

            return 447.593 + (weight * 9.247) + (height * 3.098) - (age * 4.33);
        }

        function getNormalCalorie() {
            return parseFloat($("form#main select[name='activity'] option:selected").attr('data-value')) * parseFloat(getBMR())
        }

        function deleteItem(id) {
            alertify.confirm("در صورت تایید، این آیتم حذف خواهد شد.", function (ev) {
                ev.preventDefault();
                $.ajax({
                    type: "post",
                    url: '{{ Route('admin_user_manage') }}',
                    dataType: "json",
                    data: {
                        '_token': $("meta[name='csrf-token']").attr('content'),
                        'id': id,
                        'action': 'delete'
                    },
                    success: function (response) {
                        if (response['success']) {
                            alertify.success(response['message']);
                            setTimeout(function () {
                                window.location.href = '{{ Route('admin_user_manage') }}'
                            }, 1500)
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

        $inputDecreaseOfIncreaseCoefficient.on('change', function () {
            if(!isCompleted())
                return

            let $val = $(this).val()
            if ($val > 20 || $val < -20)
                alertify.error('هشدار! ضریب کاهش یا افزایش بهتر است بین +20 تا -20 در نوسان باشد.')

            let normalCalorie = getNormalCalorie()
            let newCalorie = (normalCalorie + (normalCalorie * (parseFloat($(this).val()) / 100.0))).toFixed()

            $finalCalorieSeekbar.update({
                from: newCalorie,
                to: newCalorie,
                max: 5000
            });
            $calorieEditText.val(newCalorie).trigger("change")
        });

        function writeInit(user) {
            if(!isCompleted())
                return

            let calorieValue
            let proteinValue
            let carbsValue
            let fatValue

            let normalCalorie = getNormalCalorie()
            let proteinDivider = parseInt(normalCalorie / 4.0)
            let carbsDivider = parseInt(normalCalorie / 4.0)
            let fatDivider = parseInt(normalCalorie / 9.0)

            $inputDecreaseOfIncreaseCoefficient.val(user['decrease_or_increase_coefficient'])
            calorieValue = user['calorie_ratio']
            proteinValue = user['protein_ratio']
            carbsValue = user['carbs_ratio']
            fatValue = user['fat_ratio']

            $finalCalorieSeekbar.update({
                from: calorieValue,
                to: calorieValue,
                max: 5000
            });

            $finalCarbsSeekbar.update({
                from: carbsValue,
                to: carbsValue,
                max: carbsDivider
            });

            $finalProteinSeekbar.update({
                from: proteinValue,
                to: proteinValue,
                max: proteinDivider
            });


            $finalFatSeekbar.update({
                from: fatValue,
                to: fatValue,
                max: fatDivider
            });

            $calorieEditText.prop('value', calorieValue.toFixed())

            writeInfo()
        }

        function writeInfo() {
            let calorie = $finalCalorieSeekbar.result.from
            let protein = $finalProteinSeekbar.result.from
            let carbs = $finalCarbsSeekbar.result.from
            let fat = $finalFatSeekbar.result.from
            let decreaseOrIncreaseCoefficient = parseFloat($inputDecreaseOfIncreaseCoefficient.val())

            if (decreaseOrIncreaseCoefficient > 0)
                $status.val('ساخت عضله')
            else if (decreaseOrIncreaseCoefficient === 0)
                $status.val('نگهداری وزن');
            else
                $status.val('کات (کاهش چربی)');

            $finalCalorie.text(calorie)
            $finalCarbs.text(carbs)
            $finalProtein.text(protein)
            $finalFat.text(fat)
        }

        $calorieEditText.on('change', function () {
            //main calorie changes, depends on this
            let newCalorie = $(this).val()
            $finalCalorieSeekbar.update({
                from: newCalorie,
                to: newCalorie,
                max: 5000
            });

            let newProtein = $finalCalorieSeekbar.result.from - 4 * $finalCarbsSeekbar.result.from - 9 * $finalFatSeekbar.result.from
            newProtein /= 4

            $finalProteinSeekbar.update({
                from: newProtein,
                to: newProtein
            });

            let normalCalorie = getNormalCalorie()
            let percent = newCalorie - normalCalorie

            let proteinPercent
            let carbsPercent
            let fatPercent

            if (percent > 0) {
                proteinPercent = 40
                carbsPercent = 35
                fatPercent = 25
            } else if (percent === 0) {
                proteinPercent = 37.5
                carbsPercent = 37.5
                fatPercent = 25
            } else {
                proteinPercent = 35
                carbsPercent = 40
                fatPercent = 25
            }

            let proteinValue = (((proteinPercent / 100.0) * newCalorie) / 4.0).toFixed()
            let carbsValue = (((carbsPercent / 100.0) * newCalorie) / 4.0).toFixed()
            let fatValue = (((fatPercent / 100.0) * newCalorie) / 9.0).toFixed()

            $finalProteinSeekbar.update({
                from: proteinValue,
                to: proteinValue,
            });

            $finalCarbsSeekbar.update({
                from: carbsValue,
                to: carbsValue,
            });

            $finalFatSeekbar.update({
                from: fatValue,
                to: fatValue,
            });

            percent /= normalCalorie
            percent = (percent * 100).toFixed()
            $inputDecreaseOfIncreaseCoefficient.val(percent)

            $finalCalorie.text($finalCalorieSeekbar.result.from)
            $finalProtein.text($finalProteinSeekbar.result.from)
            $calorieEditText.text($finalCalorieSeekbar.result.from)

            writeInfo()
        })

        $("#final_calorie_seekbar").ionRangeSlider({
            min: 100,
            max: 5000,
            from: 550,
            skin: "round",
            onFinish: function (data) {
                $calorieEditText.val($finalCalorieSeekbar.result.from).trigger("change")
            },
        });

        $("#final_protein_seekbar").ionRangeSlider({
            min: 0,
            max: 1000,
            from: 550,
            skin: "round",
            onFinish: function (data) {
                let newCarbs = $finalCalorieSeekbar.result.from - 4 * $finalProteinSeekbar.result.from - 9 * $finalFatSeekbar.result.from
                newCarbs /= 4
                $finalCarbsSeekbar.update({
                    from: newCarbs,
                    to: newCarbs
                });

                $finalCarbs.text($finalCarbsSeekbar.result.from)
                $finalProtein.text($finalProteinSeekbar.result.from)
            }
        });

        $("#final_carbs_seekbar").ionRangeSlider({
            min: 0,
            max: 1000,
            from: 550,
            skin: "round",
            onFinish: function (data) {
                let newFat = $finalCalorieSeekbar.result.from - 4 * $finalProteinSeekbar.result.from - 4 * $finalCarbsSeekbar.result.from
                newFat /= 9
                $finalFatSeekbar.update({
                    from: newFat,
                    to: newFat
                });

                $finalCarbs.text($finalCarbsSeekbar.result.from)
                $finalFat.text($finalFatSeekbar.result.from)
            }
        });

        $("#final_fat_seekbar").ionRangeSlider({
            min: 0,
            max: 1000,
            from: 550,
            skin: "round",
            onFinish: function (data) {
                let newCarbs = $finalCalorieSeekbar.result.from - 4 * $finalProteinSeekbar.result.from - 9 * $finalFatSeekbar.result.from
                newCarbs /= 4
                $finalCarbsSeekbar.update({
                    from: newCarbs,
                    to: newCarbs
                });

                $finalCarbs.text($finalCarbsSeekbar.result.from)
                $finalFat.text($finalFatSeekbar.result.from)
            }
        });

        let $finalCalorieSeekbar = $("#final_calorie_seekbar").data("ionRangeSlider");
        let $finalProteinSeekbar = $("#final_protein_seekbar").data("ionRangeSlider");
        let $finalCarbsSeekbar = $("#final_carbs_seekbar").data("ionRangeSlider");
        let $finalFatSeekbar = $("#final_fat_seekbar").data("ionRangeSlider");

        $(document).ready(function () {
            @if($type == 'edit')
            writeInit({!! $user !!})
            @endif
        })
    </script>
@endsection
@include('admin.theme.master')
