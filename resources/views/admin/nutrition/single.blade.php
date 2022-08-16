@php
    $type = isset($program) ? 'edit' : 'add';
@endphp
@if($type == 'edit')
@section('buttons')
    <button class="btn btn-secondary" type="button"
            onclick="window.location.href='{{ route('admin_diet_program_details', ['id' => $program->id]) }}'">
        مشاهده برنامه
    </button>

    <button class="btn btn-secondary" type="button"
            onclick="window.location.href='{{ route('admin_diet_program_pdf_output', ['id' => $program->id]) }}'">
        دریافت نسخه PDF
    </button>
@endsection
@endif
@section('header')
    <style>
        .irs--round .irs-min, .irs--round .irs-max, .irs--round .irs-from, .irs--round .irs-to, .irs--round .irs-single {
            direction: ltr !important;
        }
    </style>
@endsection
@section('modal')
    <div class="modal fade" id="foodModal" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">افزودن غذا</h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="بستن">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <input type="hidden" name="id">

                        <div class="form-group">
                            <label>انتخاب غذا</label>
                            <div class="clearfix"></div>
                            <select class="form-control col-12" id="select3" name="food_id"></select>
                        </div>

                        <div id="unit_section" style="display: none">

                            <div class="row mt-2">
                                <div class="col-12 col-md-8 col-lg-8">

                                    <div class="form-group">
                                        <input type="text" class="form-control" name="value"/>
                                    </div>

                                </div>

                                <div class="col-12 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <select name="unit" class="form-control">
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    کالری: <span id="food_unit_calorie"></span>
                                </div>
                                <div class="col-md-6">
                                    پروتئین: <span id="food_unit_protein"></span>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    کربوهیدرات: <span id="food_unit_carbs"></span>
                                </div>
                                <div class="col-md-6">
                                    چربی: <span id="food_unit_fat"></span>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="button" data-dismiss="modal">بستن</button>
                        <button class="btn btn-primary" type="submit">تایید</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="send_user_modal" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">ارسال برنامه</h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="بستن">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <input type="hidden" name="id">

                        <div class="form-group">
                            <label>انتخاب کاربر</label>
                            <div class="clearfix"></div>
                            <select class="form-control col-12" name="user"></select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="button" data-dismiss="modal">بستن</button>
                        <button class="btn btn-primary" type="submit">تایید</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="supplement_modal" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">افزودن مکمل</h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="بستن">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <input type="hidden" name="id">

                        <div class="form-group">
                            <label>انتخاب مکمل</label>
                            <div class="clearfix"></div>
                            <select class="form-control col-12" name="supplement_id"></select>
                        </div>

                        <div id="unit_section" style="display: none">
                            <label>انتخاب واحد:</label>
                            <div class="row mt-2">
                                <div class="col-12 col-md-8 col-lg-8">

                                    <div class="form-group">
                                        <input type="text" class="form-control" name="value"/>
                                    </div>

                                </div>

                                <div class="col-12 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <select name="unit" class="form-control">
                                        </select>
                                    </div>
                                </div>

                            </div>


                            <div>
                                <label>انتخاب واحد به صورت دستی:</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="unit_text"/>
                                </div>

                            </div>


                            <div class="form-group">
                                <label>توضیحات:</label>
                                <textarea class="form-control" name="text"></textarea>

                                <script>
                                    CKEDITOR.replace('text');
                                </script>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="button" data-dismiss="modal">بستن</button>
                        <button class="btn btn-primary" type="submit">تایید</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('container')
    <div class="card card-default">
        <div class="card-body">
            <form id="main">

                <div class="form-group">
                    <label>انتخاب کاربر:</label>
                    <select class="form-control col-12" id="select2"
                            name="user_id" @if($type == 'edit'){{ 'disabled readonly' }}@endif>
                    </select>
                </div>

                <div id="block1_extra" style="display: none">
                    <div class="form-group">
                        <label>ضریب کاهش یا افزایش:</label>
                        <input type="tel" value="0" class="form-control col-12"
                               name="decrease_or_increase_coefficient">
                    </div>
                </div>

            </form>
        </div>
    </div>

    <div id="user_information" style="display: none">
        <div class="row" id="user_customer_alert" style="display: none">
            <div class="col col-12">
                <div class="alert alert-warning">این کاربر جز لیست مشتریان ویژه نیست.</div>
            </div>
        </div>

        <div class="row" id="user_diet_program_expired_alert" style="display: none">
            <div class="col col-12">
                <div class="alert alert-warning" id="text"></div>
            </div>
        </div>

        <div class="row" id="user_information">
            <div class="col col-12 col-md-6 col-lg-3">
                <div class="card card-default">
                    <div class="card-body">
                        <h4 class="mt-0 header-title mb-4">اطلاعات کاربری</h4>
                        <table class="table table-striped mb-0">
                            <tbody>
                            <tr>
                                <td><i class="far fa-calendar text-primary h2"></i></td>
                                <td>
                                    <h6 class="mt-0" id="age_info"></h6>
                                    <p class="text-muted mb-0">سن</p></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-weight text-primary h2"></i></td>
                                <td>
                                    <h6 class="mt-0" id="weight_info"></h6>
                                    <p class="text-muted mb-0">وزن</p></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-ruler text-primary h2"></i></td>
                                <td>
                                    <h6 class="mt-0" id="height_info"></h6>
                                    <p class="text-muted mb-0">قد</p></td>
                            </tr>
                            <tr>
                                <td><i class="far fa-user text-primary h2"></i></td>
                                <td>
                                    <h6 class="mt-0" id="gender_info"></h6>
                                    <p class="text-muted mb-0">جنسیت</p></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-running text-primary h2"></i></td>
                                <td>
                                    <h6 class="mt-0" id="activity_info"></h6>
                                    <p class="text-muted mb-0">سطح فعالیت</p></td>
                            </tr>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

            <div class="col col-12 col-md-6 col-lg-3">
                <div class="card card-default">
                    <div class="card-body">
                        <h4 class="mt-0 header-title mb-4">تنظیمات برنامه شما</h4>
                        <table class="table table-striped mb-0">
                            <tbody>
                            <tr>
                                <td><i class="fas fa-cogs text-primary h2"></i></td>
                                <td>
                                    <h6 class="mt-0" id="status"></h6>
                                    <p class="text-muted mb-0">وضعیت</p></td>
                            </tr>

                            <tr>
                                <td><i class="fas fa-burn text-primary h2"></i></td>
                                <td>
                                    <h6 class="mt-0" id="calorie_info"></h6>
                                    <p class="text-muted mb-0">کالری</p></td>
                            </tr>

                            <tr>
                                <td><i class="fa fa-archive text-primary h2"></i></td>
                                <td>
                                    <h6 class="mt-0" id="protein_info"></h6>
                                    <p class="text-muted mb-0">پروتئین</p></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-archive text-primary h2"></i></td>
                                <td>
                                    <h6 class="mt-0" id="carbs_info"></h6>
                                    <p class="text-muted mb-0">کربوهیدرات</p></td>
                            </tr>
                            <tr>
                                <td><i class="fa fa-burn text-primary h2"></i></td>
                                <td>
                                    <h6 class="mt-0" id="fat_info"></h6>
                                    <p class="text-muted mb-0">چربی</p></td>
                            </tr>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

            <div class="col col-12 col-md-6 col-lg-3">

                <div class="card card-default">
                    <div class="card-body">
                        <h4 class="mt-0 header-title mb-4">تنظیمات کاربر</h4>
                        <table class="table table-striped mb-0">
                            <tbody>
                            <tr>
                                <td><i class="fas fa-ruler text-primary h2"></i></td>
                                <td>
                                    <h6 class="mt-0" id="user_decrease_or_increase_coefficient"></h6>
                                    <p class="text-muted mb-0">ضریب کاهش یا افزایش</p></td>
                            </tr>

                            <tr>
                                <td><i class="fas fa-burn text-primary h2"></i></td>
                                <td>
                                    <h6 class="mt-0" id="user_calorie"></h6>
                                    <p class="text-muted mb-0">کالری</p></td>
                            </tr>

                            <tr>
                                <td><i class="fas fa-fish text-primary h2"></i></td>
                                <td>
                                    <h6 class="mt-0" id="user_protein"></h6>
                                    <p class="text-muted mb-0">پروتئین</p></td>
                            </tr>

                            <tr>
                                <td><i class="fas fa-carrot text-primary h2"></i></td>
                                <td>
                                    <h6 class="mt-0" id="user_carbs"></h6>
                                    <p class="text-muted mb-0">کربوهیدرات</p></td>
                            </tr>

                            <tr>
                                <td><i class="fas fa-burn text-primary h2"></i></td>
                                <td>
                                    <h6 class="mt-0" id="user_fat"></h6>
                                    <p class="text-muted mb-0">چربی</p></td>
                            </tr>

                            </tbody>
                        </table>

                    </div>
                </div>

            </div>


            <div class="col col-12 col-md-6 col-lg-3">

                <div class="card card-default">
                    <div class="card-body">
                        <h4 class="mt-0 header-title mb-4">سایر اطلاعات</h4>
                        <table class="table table-striped mb-0">
                            <tbody>
                            <tr>
                                <td><i class="fas fa-ruler text-primary h2"></i></td>
                                <td>
                                    <h6 class="mt-0" id="bmi_info"></h6>
                                    <p class="text-muted mb-0">BMI</p></td>
                            </tr>

                            <tr>
                                <td><i class="fa fa-ruler text-primary h2"></i></td>
                                <td>
                                    <h6 class="mt-0" id="bmr_info"></h6>
                                    <p class="text-muted mb-0">BMR</p></td>
                            </tr>

                            </tbody>
                        </table>

                    </div>
                </div>

            </div>


        </div>


        <div class="row">
            <div class="col-12">
                <div class="card card-default">
                    <div class="card-body">
                        <h4 class="mt-0 header-title mb-4">انتخاب مقادیر مورد نظر</h4>

                        <div class="row mt-4">
                            <div class="col-2 col-sm-3 col-lg-2">
                                <div class="form-group">
                                    <label>کالری نهایی: (<span id="final_calorie"></span> )</label>
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
                                    <label>پروتئین نهایی: (<span style="font-weight: bold" id="final_protein"></span> )</label>
                                </div>
                            </div>

                            <div class="col-7">
                                <input type="text" id="final_protein_seekbar">
                            </div>

                        </div>


                        <div class="row" style="margin-top: 15px">
                            <div class="col-5">
                                <div class="form-group">
                                    <label>کربوهیدرات نهایی: (<span style="font-weight: bold" id="final_carbs"></span>
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
                                    <label>چربی نهایی: (<span style="font-weight: bold" id="final_fat"></span> )</label>
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


        <div class="row">

            <div class="col-12 col-md-6 col-lg-6">
                <div class="card card-default">
                    <div class="card-body">
                        <h4 class="mt-0 header-title mb-4">نمودار مصرف هفته</h4>
                        <table class="table table-striped mb-0">
                            <tbody>
                            <tr>
                                <td class="col-4">کالری</td>
                                <td class="col-8">
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar bg-success" role="progressbar"
                                             id="calorie_progress_bar"
                                             aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td class="col-4">پروتئین</td>
                                <td class="col-8">
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar bg-info" role="progressbar"
                                             id="protein_progress_bar"
                                             aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td class="col-4">کربوهیدرات</td>
                                <td class="col-8">
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar bg-warning" role="progressbar" id="carbs_progress_bar"
                                             aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td class="col-4">چربی</td>
                                <td class="col-8">
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar bg-danger" role="progressbar" id="fat_progress_bar"
                                             aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                            </tr>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>


            <div class="col-12 col-md-6 col-lg-6">
                <div class="card card-default">
                    <div class="card-body">
                        <h4 class="mt-0 header-title mb-4">اطلاعات کلی هفته</h4>
                        <table class="table table-striped mb-0">
                            <tbody>
                            <tr>
                                <td class="col-4">کالری باقیمانده</td>
                                <td class="col-8" id="remaining_calorie">-</td>
                            </tr>

                            <tr>
                                <td class="col-4">پروتئین باقیمانده</td>
                                <td class="col-8" id="remaining_protein"></td>
                            </tr>

                            <tr>
                                <td class="col-4">کربوهیدرات باقیمانده</td>
                                <td class="col-8" id="remaining_carbs"></td>
                            </tr>

                            <tr>
                                <td class="col-4">چربی باقیمانده</td>
                                <td class="col-8" id="remaining_fat"></td>
                            </tr>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>


        <div class="row">

            <div class="col-12 col-md-6 col-lg-6">
                <div class="card card-default">
                    <div class="card-body">
                        <h4 class="mt-0 header-title mb-4">کالری باقیمانده در طول هفته</h4>
                        <table class="table table-striped mb-0">
                            <tbody>
                            <tr>
                                <td class="col-4">شنبه</td>
                                <td class="col-8" id="saturday_calorie">-</td>
                            </tr>

                            <tr>
                                <td class="col-4">یکشنبه</td>
                                <td class="col-8" id="sunday_calorie"></td>
                            </tr>

                            <tr>
                                <td class="col-4">دوشنبه</td>
                                <td class="col-8" id="monday_calorie"></td>
                            </tr>

                            <tr>
                                <td class="col-4">سه شنبه</td>
                                <td class="col-8" id="tuesday_calorie"></td>
                            </tr>

                            <tr>
                                <td class="col-4">چهارشنبه</td>
                                <td class="col-8" id="wednesday_calorie"></td>
                            </tr>

                            <tr>
                                <td class="col-4">پنج شنبه</td>
                                <td class="col-8" id="thursday_calorie"></td>
                            </tr>

                            <tr>
                                <td class="col-4">جمعه</td>
                                <td class="col-8" id="friday_calorie"></td>
                            </tr>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-6">
                <div class="card card-default">
                    <div class="card-body">
                        <h4 class="mt-0 header-title mb-4">پروتئین باقیمانده در طول هفته</h4>
                        <table class="table table-striped mb-0">
                            <tbody>
                            <tr>
                                <td class="col-4">شنبه</td>
                                <td class="col-8" id="saturday_protein">-</td>
                            </tr>

                            <tr>
                                <td class="col-4">یکشنبه</td>
                                <td class="col-8" id="sunday_protein"></td>
                            </tr>

                            <tr>
                                <td class="col-4">دوشنبه</td>
                                <td class="col-8" id="monday_protein"></td>
                            </tr>

                            <tr>
                                <td class="col-4">سه شنبه</td>
                                <td class="col-8" id="tuesday_protein"></td>
                            </tr>

                            <tr>
                                <td class="col-4">چهارشنبه</td>
                                <td class="col-8" id="wednesday_protein"></td>
                            </tr>

                            <tr>
                                <td class="col-4">پنج شنبه</td>
                                <td class="col-8" id="thursday_protein"></td>
                            </tr>

                            <tr>
                                <td class="col-4">جمعه</td>
                                <td class="col-8" id="friday_protein"></td>
                            </tr>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

        <div class="row supplement">
            <div class="col-12">
                <div class="card card-default">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                            </div>
                            <div class="col-md-12">
                                <div class="float-right d-none d-md-block">
                                    <button class="btn btn-outline-primary" onclick="addSupplement($(this))">افزودن
                                        مکمل
                                    </button>
                                </div>
                            </div>
                            <!-- end col -->
                        </div>
                    </div>

                    <div class="card-body">

                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">نام مکمل</th>
                                <th scope="col">مقدار</th>
                                <th scope="col">توضیحات</th>
                                <th scope="col" width="10%">عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

        </div>


        <div id="meals"></div>

        <div class="text-center mt-2">
            <button class="btn btn-outline-success" onclick="addMeal()"><i class="mdi mdi-plus"></i> افزودن وعده غذایی
            </button>

            <button class="btn btn-outline-purple" onclick="addMeals()"><i class="mdi mdi-plus"></i> افزودن وعده های
                غذایی آماده
            </button>
        </div>

        <div class="row mt-4">

            <div class="col-12">
                <div class="card card-default">
                    <div class="card-body">
                        <h4 class="mt-0 header-title mb-4">یادداشت برای مشتری</h4>
                        <textarea name="note" class="form-control" rows="6"
                                  placeholder="اختیاری..."></textarea>
                    </div>
                </div>
            </div>

        </div>


        <div class="mt-4 text-center pb-5">
            <button class="btn btn-danger" onclick="submit()"><i class="mdi mdi-check"></i> ارسال برنامه تغذیه</button>
            @if($type == 'edit')
                <button class="btn btn-primary mr-1" data-toggle="modal" data-target="#send_user_modal"><i
                        class="mdi mdi-forward"></i> ارسال مجدد
                    برنامه ویرایش شده
                    @endif
                </button>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let oldProteinPercent = 0;
        let oldFatPercent = 0;
        let oldCarbsPercent = 0;
        let $calorieEditText = $("#calorie_edit_text")
        let $inputDecreaseOfIncreaseCoefficient = $("input[name='decrease_or_increase_coefficient']")

        function roundNumber(number) {
            return Math.round(number * 100) / 100
        }

        function writeInfo() {
            let calorie = $finalCalorieSeekbar.result.from
            let protein = $finalProteinSeekbar.result.from
            let carbs = $finalCarbsSeekbar.result.from
            let fat = $finalFatSeekbar.result.from
            let decreaseOrIncreaseCoefficient = parseFloat($inputDecreaseOfIncreaseCoefficient.val())

            if (decreaseOrIncreaseCoefficient > 0)
                $status.html('ساخت عضله')
            else if (decreaseOrIncreaseCoefficient === 0)
                $status.html('نگهداری وزن');
            else
                $status.html('کات (کاهش چربی)');

            $calorieInfo.html(calorie)
            $proteinInfo.html(protein)
            $carbsInfo.html(carbs)
            $fatInfo.html(fat)

            $finalCalorie.text(calorie)
            $finalCarbs.text(carbs)
            $finalProtein.text(protein)
            $finalFat.text(fat)
        }

        $inputDecreaseOfIncreaseCoefficient.on('change', function () {
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
            operateProgressBars()

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
            max: 5000,
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
                operateProgressBars()

                // let newCal = 4 * $finalProteinSeekbar.result.from + 4 * $finalCarbsSeekbar.result.from + 9 * $finalFatSeekbar.result.from
                // $finalCalorieSeekbar.update({
                //     from: newCal,
                //     to: newCal
                // });
                //
                // $finalCalorie.text($finalCalorieSeekbar.result.from)
                // $calorieEditText.val($finalCalorieSeekbar.result.from)
                // $finalProtein.text($finalProteinSeekbar.result.from)
                operateProgressBars()
            }
        });

        $("#final_carbs_seekbar").ionRangeSlider({
            min: 0,
            max: 5000,
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
                operateProgressBars()
            }
        });

        $("#final_fat_seekbar").ionRangeSlider({
            min: 0,
            max: 5000,
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
                operateProgressBars()
            }
        });

        let $finalCalorieSeekbar = $("#final_calorie_seekbar").data("ionRangeSlider");
        let $finalProteinSeekbar = $("#final_protein_seekbar").data("ionRangeSlider");
        let $finalCarbsSeekbar = $("#final_carbs_seekbar").data("ionRangeSlider");
        let $finalFatSeekbar = $("#final_fat_seekbar").data("ionRangeSlider");

        function submit(userId = null) {
            if (userId != null) {
                requestSubmit(userId)
                return
            }

            alertify.confirm("در صورت تایید، این برنامه برای کاربر ارسال خواهد شد. ادامه می دهید؟", function (ev) {
                requestSubmit(userId)
                ev.preventDefault()
            }, function (ev) {
                ev.preventDefault()
            })
        }

        function requestSubmit(userId = null) {
            let $meals = $("div.meal")

            if ($meals.length === 0) {
                alertify.error('هیچ وعده غذایی یافت نشد.')
                return
            }

            let mealsArray = []
            let canContinue = true
            $meals.each(function (i) {
                let $this = $(this)
                let nameFa = $this.find("input[name='name_fa']").val()
                let nameEn = $this.find("input[name='name_en']").val()
                let day = $this.find("select[name='day']").val()
                let meal = $this.find("select[name='meal']").val()

                if (meal === null) {
                    alertify.error('لطفا وعده غذایی شماره ' + (i + 1) + ' را انتخاب کنید.')
                    canContinue = false
                    return
                }

                if (nameFa === '') {
                    alertify.error('لطفا نام فارسی وعده غذایی شماره ' + (i + 1) + ' را وارد کنید.')
                    canContinue = false
                    return
                }

                if (nameEn === '') {
                    alertify.error('لطفا نام انگلیسی وعده غذایی شماره ' + (i + 1) + ' را وارد کنید.')
                    canContinue = false
                    return
                }

                let $foods = $this.find(".foods span")

                if ($foods.length === 0) {
                    alertify.error('هیچ غذایی در ' + name + ' شماره ' + (i + 1) + ' یافت نشد.')
                    canContinue = false
                    return
                }

                let itemsArray = []
                $foods.each(function () {
                    itemsArray.push({
                        'food_id': $(this).attr('data-food-id'),
                        'unit_id': $(this).attr('data-unit-id'),
                        'value': $(this).attr('data-value')
                    })
                })
                mealsArray.push({
                    'name_fa': nameFa,
                    'name_en': nameEn,
                    'day': day,
                    'meal': meal,
                    'items': itemsArray
                })
            })

            let supplementArray = []
            $("div.supplement tbody tr").each(function () {
                supplementArray.push({
                    'supplement_id': $(this).attr('data-supplement-id'),
                    'unit_id': $(this).attr('data-unit-id'),
                    'value': $(this).attr('data-value'),
                    'unit_text': $(this).attr('data-unit-text'),
                    'text': $(this).find("td:eq(3)").html(),
                })
            })

            if (!canContinue)
                return

            setFormSubmitting()
            $.ajax({
                type: "post",
                url: '',
                dataType: "json",
                data: {
                    '_token': $("meta[name='csrf-token']").attr('content'),
                    'user_id': userId == null ? $("#select2").val() : userId,
                    'send_again': userId != null ? '1' : '0',
                    'meals': JSON.stringify(mealsArray),
                    'supplements': JSON.stringify(supplementArray),
                    'protein': $finalProtein.text(),
                    'carbs': $finalCarbs.text(),
                    'fat': $finalFat.text(),
                    'decrease_or_increase_coefficient': decreaseOrIncreaseCoefficient(),
                    'note': $("textarea[name='note']").val(),
                },
                success: function (response) {
                    if (response['success']) {
                        alertify.success(response['message']);
                        setTimeout(function () {
                            window.location.href = response['redirect_url']
                        }, 1000)
                    } else
                        alertify.error(response['message']);
                },
                error: function () {
                    alertify.error('خطا رخ داد.');
                }
            })
        }

        let $modalFood = $("#select3")
        let $modalSupplement = $("#supplement_modal select[name='supplement_id']")
        let $modalUnitCalorie = $("#foodModal #unit_section #food_unit_calorie")
        let $modalUnitProtein = $("#foodModal #unit_section #food_unit_protein")
        let $modalUnitCarbs = $("#foodModal #unit_section #food_unit_carbs")
        let $modalUnitFat = $("#foodModal #unit_section #food_unit_fat")

        $(document).ready(function () {
            $modalFood.select2({
                minimumInputLength: 2,
                placeholder: "غذا را انتخاب کنید...",
                allowClear: true,
                ajax: {
                    url: '{{ route('admin_diet_program_action', ['action' => 'food-search']) }}',
                    dataType: 'json',
                    type: "POST",
                    quietMillis: 50,
                    data: function (term) {
                        return {
                            q: term.term,
                            _token: $("meta[name='csrf-token']").attr('content'),
                        };
                    },
                    processResults: function (data) {
                        if (data.success) {
                            const myResults = [];
                            $.each(data['items'], function (index, item) {
                                myResults.push({
                                    'id': item.id,
                                    'text': item['name_fa'],
                                });
                            });
                            return {
                                results: myResults
                            };
                        }
                    }
                }
            }).on('change', function () {
                let $food = $(this);
                $.ajax({
                    type: "post",
                    url: '{{ route('admin_diet_program_action', ['action' => 'check-food']) }}',
                    dataType: "json",
                    data: {
                        '_token': $("meta[name='csrf-token']").attr('content'),
                        'id': $food.val(),
                    },
                    success: function (response) {
                        if (response['success']) {
                            let unitEl = $("#foodModal select[name='unit']")
                            unitEl.html('')
                            let food = response['food'];
                            $("#foodModal #unit_section").show();

                            let units = food['units']
                            let html = ''

                            for (let i = 0; i < units.length; i++)
                                html += '<option value="' + i + '">' + food['units'][i]['name_fa'] + '</option>'

                            unitEl.html(html)

                            $food.attr('data-unit-name', food['units'][0]['name_fa'])
                                .attr('data-unit-id', food['units'][0]['id'])
                                .attr('data-food-id', food['id'])
                                .attr('data-calorie-value', food['units'][0]['real_calorie'])
                                .attr('data-protein-value', food['units'][0]['real_protein'])
                                .attr('data-carbs-value', food['units'][0]['real_carbs'])
                                .attr('data-fat-value', food['units'][0]['real_fat'])
                                .attr('data-food-name', food['name_fa'])

                            unitEl.on('change', function () {
                                let i = $(this).val()
                                $food.attr('data-unit-name', food['units'][i]['name_fa'])
                                    .attr('data-unit-id', food['units'][i]['id'])
                                    .attr('data-food-id', food['id'])
                                    .attr('data-calorie-value', food['units'][i]['real_calorie'])
                                    .attr('data-protein-value', food['units'][i]['real_protein'])
                                    .attr('data-carbs-value', food['units'][i]['carbs'])
                                    .attr('data-fat-value', food['units'][i]['real_fat'])
                                    .attr('data-food-name', food['name_fa'])
                                $("#foodModal input[name='value']").trigger('keyup')
                            })
                        } else
                            alertify.error(response['message']);
                    },
                    error: function () {
                        $("#foodModal #unit_section").hide();
                        $("#foodModal form").trigger('reset')
                        $modalUnitCalorie.text('')
                        $modalUnitCarbs.text('')
                        $modalUnitFat.text('')
                        $modalUnitProtein.text('')
                    }
                })
            })

            $modalSupplement.select2({
                minimumInputLength: 2,
                placeholder: "مکمل را انتخاب کنید...",
                allowClear: true,
                ajax: {
                    url: '{{ route('admin_diet_program_action', ['action' => 'food-search']) }}',
                    dataType: 'json',
                    type: "POST",
                    quietMillis: 50,
                    data: function (term) {
                        return {
                            q: term.term,
                            _token: $("meta[name='csrf-token']").attr('content'),
                        };
                    },
                    processResults: function (data) {
                        if (data.success) {
                            const myResults = [];
                            $.each(data['items'], function (index, item) {
                                myResults.push({
                                    'id': item.id,
                                    'text': item['name_fa'],
                                });
                            });
                            return {
                                results: myResults
                            };
                        }
                    }
                }
            }).on('change', function () {
                let $supplement = $(this);
                $.ajax({
                    type: "post",
                    url: '{{ route('admin_diet_program_action', ['action' => 'check-food']) }}',
                    dataType: "json",
                    data: {
                        '_token': $("meta[name='csrf-token']").attr('content'),
                        'id': $supplement.val(),
                    },
                    success: function (response) {
                        if (response['success']) {
                            let unitEl = $("#supplement_modal select[name='unit']")
                            unitEl.html('')
                            let supplement = response['food'];
                            $("#supplement_modal #unit_section").show();

                            let units = supplement['units']
                            let html = ''

                            for (let i = 0; i < units.length; i++)
                                html += '<option value="' + i + '">' + supplement['units'][i]['name_fa'] + '</option>'

                            unitEl.html(html)

                            console.log(supplement)

                            $supplement.attr('data-unit-name', supplement['units'][0]['name_fa'])
                                .attr('data-unit-id', supplement['units'][0]['id'])
                                .attr('data-supplement-id', supplement['id'])
                                .attr('data-calorie-value', supplement['units'][0]['real_calorie'])
                                .attr('data-protein-value', supplement['units'][0]['real_protein'])
                                .attr('data-carbs-value', supplement['units'][0]['real_carbs'])
                                .attr('data-fat-value', supplement['units'][0]['real_fat'])
                                .attr('data-supplement-name', supplement['name_fa'])

                            unitEl.on('change', function () {
                                let i = $(this).val()
                                $supplement.attr('data-unit-name', supplement['units'][i]['name_fa'])
                                    .attr('data-unit-id', supplement['units'][i]['id'])
                                    .attr('data-supplement-id', supplement['id'])
                                    .attr('data-calorie-value', supplement['units'][i]['real_calorie'])
                                    .attr('data-protein-value', supplement['units'][i]['real_protein'])
                                    .attr('data-carbs-value', supplement['units'][i]['carbs'])
                                    .attr('data-fat-value', supplement['units'][i]['real_fat'])
                                    .attr('data-supplement-name', supplement['name_fa'])
                                $("#foodModal input[name='value']").trigger('keyup')
                            })

                        } else
                            alertify.error(response['message']);
                    },
                    error: function () {
                        $("#supplement_modal #unit_section").hide();
                        $("#supplement_modal form").trigger('reset')
                        $modalSupplement.find("textarea[name='text']").val('')
                    }
                })
            })

            $("#foodModal input[name='value']").on('keyup', function () {
                let value = $(this).val()
                if (value === '')
                    return

                if (value === '' || isNaN(value)) {
                    $modalUnitCalorie.text('')
                    $modalUnitProtein.text('')
                    $modalUnitCarbs.text('')
                    $modalUnitFat.text('')
                    return
                }

                value = parseFloat(value)

                let calorie = parseFloat($modalFood.attr('data-calorie-value'))
                let protein = parseFloat($modalFood.attr('data-protein-value'))
                let carbs = parseFloat($modalFood.attr('data-carbs-value'))
                let fat = parseFloat($modalFood.attr('data-fat-value'))

                $modalUnitCalorie.text((value * calorie).toFixed(2))
                $modalUnitCarbs.text((value * carbs).toFixed(2))
                $modalUnitFat.text((value * fat).toFixed(2))
                $modalUnitProtein.text((value * protein).toFixed())
            })

            $("#select2").select2({
                minimumInputLength: 2,
                placeholder: "کاربر را انتخاب کنید...",
                allowClear: true,
                ajax: {
                    url: '{{ Route('admin_diet_program_action', ['action' => 'user-search']) }}',
                    dataType: 'json',
                    type: "POST",
                    quietMillis: 50,
                    data: function (term) {
                        return {
                            q: term.term,
                            _token: $("meta[name='csrf-token']").attr('content'),
                        };
                    },
                    processResults: function (data) {
                        if (data.success) {
                            const myResults = [];
                            $.each(data['items'], function (index, item) {
                                myResults.push({
                                    'id': item.id,
                                    'text': item['first_name'] + ' ' + item['last_name'] + ' (' + (
                                        item['contact']
                                    ) + ')',
                                });
                            });
                            return {
                                results: myResults
                            };
                        }
                    }
                }
            });


            $("#send_user_modal select[name='user']").select2({
                minimumInputLength: 2,
                placeholder: "کاربر را انتخاب کنید...",
                allowClear: true,
                ajax: {
                    url: '{{ Route('admin_diet_program_action', ['action' => 'user-search']) }}',
                    dataType: 'json',
                    type: "POST",
                    quietMillis: 50,
                    data: function (term) {
                        return {
                            q: term.term,
                            _token: $("meta[name='csrf-token']").attr('content'),
                        };
                    },
                    processResults: function (data) {
                        if (data.success) {
                            const myResults = [];
                            $.each(data['items'], function (index, item) {
                                myResults.push({
                                    'id': item.id,
                                    'text': item['first_name'] + ' ' + item['last_name'] + ' (' + (
                                        item['contact']
                                    ) + ')',
                                });
                            });
                            return {
                                results: myResults
                            };
                        }
                    }
                }
            });
        });

        let $calorieProgressBar = $("#calorie_progress_bar")
        let $fatProgressBar = $("#fat_progress_bar")
        let $proteinProgressBar = $("#protein_progress_bar")
        let $carbsProgressBar = $("#carbs_progress_bar")

        let $status = $("h6#status")
        let $activity = $("h6#activity_info")
        let $bmr = $("h6#bmr_info")
        let $calorieInfo = $("h6#calorie_info")
        let $proteinInfo = $("h6#protein_info")
        let $fatInfo = $("h6#fat_info")
        let $carbsInfo = $("h6#carbs_info")

        let proteinPercentage = 0
        let fatPercentage = 0
        let carbsPercentage = 0

        let $finalCalorie = $("span#final_calorie")
        let $finalProtein = $("span#final_protein")
        let $finalCarbs = $("span#final_carbs")
        let $finalFat = $("span#final_fat")

        let $meals = $("div#meals")

        $("#select2").on('change', function () {
            let id = $(this).val()
            let $userInformation = $("#user_information")
            let ageInfo = $("h6#age_info")
            let $heightInfo = $("h6#height_info")
            let $weightInfo = $("h6#weight_info")
            let $genderInfo = $("h6#gender_info")
            let $bmiInfo = $("h6#bmi_info")
            let $block1Extra = $("div#block1_extra")

            $.ajax({
                type: "post",
                url: '{{ Route('admin_diet_program_action', ['action' => 'check-user']) }}',
                dataType: "json",
                data: {
                    '_token': $("meta[name='csrf-token']").attr('content'),
                    'id': id,
                },
                success: function (response) {
                    if (response['success']) {
                        let user = response['user']

                        let $userCustomerAlert = $("#user_customer_alert")
                        let $userDietProgramExpiredAlert = $("#user_diet_program_expired_alert")

                        if (!user['customer'])
                            $userCustomerAlert.show()
                        else
                            $userCustomerAlert.hide()

                        if (response['expires_message'] != null) {
                            $userDietProgramExpiredAlert.show()
                            $userDietProgramExpiredAlert.find("#text").html(response['expires_message'])
                        } else {
                            $userDietProgramExpiredAlert.hide()
                        }

                        if (user['profile_completed'] !== true) {
                            $userInformation.hide()
                            alertify.error('پروفایل کاربر کامل نیست. لطفا از کاربر بخواهید اطلاعات پروفایل خود را در نرم افزار تکمیل کند.');
                            $block1Extra.hide()
                        } else {
                            $block1Extra.show();
                            $userInformation.show()
                            ageInfo.html(user['age'])
                            $heightInfo.html(user['height'])
                            $weightInfo.html(user['weight'])
                            $genderInfo.html(user['gender_fa'])
                            $activity.html(user['activity_fa'])
                                .attr('data-content', user['activity_ratio'])
                            $bmiInfo.html(parseInt(user['bmi']))
                            $bmr.html(parseInt(user['bmr']))
                            $("#user_decrease_or_increase_coefficient").html(user['decrease_or_increase_coefficient'])
                            $("#user_calorie").html(numberFormat(user['calorie_ratio']))
                            $("#user_protein").html(numberFormat(user['protein_ratio']))
                            $("#user_carbs").html(numberFormat(user['carbs_ratio']))
                            $("#user_fat").html(numberFormat(user['fat_ratio']))

                            @if($type == 'add')
                            writeInit()
                            @else
                            initEdit({!! $program !!})
                            @endif
                        }
                    } else
                        alertify.error(response['message']);
                },
                error: function () {
                    $userInformation.hide()
                    $block1Extra.hide()
                }
            })
        });


        function decreaseOrIncreaseCoefficient() {
            return parseFloat($inputDecreaseOfIncreaseCoefficient.val())
        }

        function handleMeal() {
            $("div.meal input[name='name']").on('input', function () {
                $(this).parents("div.meal").find(".page-title").text($(this).val())
            })

            $("div.meal select[name='meal']").on('change', function () {
                switch ($(this).val()) {
                    case 'breakfast':
                        $(this).parents("div.meal").find("input[name='name_fa']").val("صبحانه")
                        $(this).parents("div.meal").find("input[name='name_en']").val("Breakfast")
                        break;

                    case 'lunch':
                        $(this).parents("div.meal").find("input[name='name_fa']").val("ناهار")
                        $(this).parents("div.meal").find("input[name='name_en']").val("Lunch")
                        break;

                    case 'dinner':
                        $(this).parents("div.meal").find("input[name='name_fa']").val("شام")
                        $(this).parents("div.meal").find("input[name='name_en']").val("Dinner")
                        break;

                    case 'snack':
                    default:
                        $(this).parents("div.meal").find("input[name='name_fa']").val("میان وعده")
                        $(this).parents("div.meal").find("input[name='name_en']").val("Snack")
                        break;

                    case 'before-workout':
                        $(this).parents("div.meal").find("input[name='name_fa']").val("قبل از تمرین")
                        $(this).parents("div.meal").find("input[name='name_en']").val("Before workout")
                        break;

                    case 'after-workout':
                        $(this).parents("div.meal").find("input[name='name_fa']").val("بعد از تمرین")
                        $(this).parents("div.meal").find("input[name='name_en']").val("After workout")
                        break;
                }
            })

            $("div.meal select[name='day']").on('change', function () {
                operateProgressBars()
            })

            function calculateMealCalorie(meal) {
                let calorie = meal.find("input[name='calorie_suggestion']")

                let fat = meal.find("input[name='fat_suggestion']")
                let carbs = meal.find("input[name='carbs_suggestion']")
                let protein = meal.find("input[name='protein_suggestion']")

                let fatValue = fat.val()
                let carbsValue = carbs.val()
                let proteinValue = protein.val()

                if (isNaN(fatValue) || isNaN(carbsValue) || isNaN(proteinValue)) {
                    fat.val('')
                    carbs.val('')
                    protein.val('')
                    return
                }
                calorie.val(parseFloat(fatValue) * 9 + parseFloat(carbsValue) * 4 + parseFloat(proteinValue) * 4)
            }

            $("div.meal input[name='protein_suggestion'],div.meal input[name='carbs_suggestion'],div.meal input[name='fat_suggestion']").change(function () {
                calculateMealCalorie($(this).parents("div.meal"))
            });

            $("div.meal input[name='calorie_suggestion']").change(function () {
                let meal = $(this).parents("div.meal")

                let fat = meal.find("input[name='fat_suggestion']")
                let carbs = meal.find("input[name='carbs_suggestion']")
                let protein = meal.find("input[name='protein_suggestion']")

                if (isNaN(meal.find("input[name='calorie_suggestion']"))) {
                    fat.val('')
                    carbs.val('')
                    protein.val('')
                    return
                }

                meal.find("input[name='fat_suggestion']").val('0')
                meal.find("input[name='carbs_suggestion']").val('0')
                meal.find("input[name='protein_suggestion']").val('0')
            });
        }

        let calorieSum = 0
        let proteinSum = 0
        let carbsSum = 0
        let fatSum = 0

        let $modalElement = null

        function requestAddFood() {
            let $modal = $("#foodModal")
            let $food = $modal.find("#select3")
            let value = $modal.find("input[name='value']").val()

            if ($food.val() == null) {
                alertify.error('لطفا غذا را وارد کنید.')
                return
            }

            if (value === '') {
                alertify.error('لطفا مقدار را وارد کنید.')
                return
            }

            if (isNaN(value)) {
                alertify.error('لطفا مقدار را صحیح وارد کنید.')
                return
            }

            value = parseFloat(value)

            if (value <= 0) {
                alertify.error('لطفا مقدار را صحیح وارد کنید.')
                return
            }

            $modal.modal('toggle')

            let calorie = (parseFloat($food.attr('data-calorie-value')) * value)
            let protein = (parseFloat($food.attr('data-protein-value')) * value)
            let fat = (parseFloat($food.attr('data-fat-value')) * value)
            let carbs = (parseFloat($food.attr('data-carbs-value')) * value)

            calorieSum += calorie
            proteinSum += protein
            fatSum += fat
            carbsSum += carbs

            let layout = $modalElement.parents("div.meal").find("div.foods")
            layout.append('<span class="btn btn-primary m-1" ' +
                'data-food-id="' + $food.attr('data-food-id') + '" ' +
                'data-food-name="' + $food.attr('data-food-name') + '"  ' +
                'data-unit-name="' + $food.attr('data-unit-name') + '"  ' +
                'data-unit-id="' + $food.attr('data-unit-id') + '"  ' +
                'data-protein-value="' + protein + '"  ' +
                'data-fat-value="' + fat + '"  ' +
                'data-carbs-value="' + carbs + '"  ' +
                'data-calorie-value="' + calorie + '"  ' +
                'data-value="' + value + '"' +
                '>' + (value + ' ' + $food.attr('data-unit-name') + ' ' + $food.attr("data-food-name")) + '<i class="mr-1 mdi mdi-delete" style="cursor: pointer" onclick="removeFood($(this).parent())"></i> <i class="mr-1 mdi mdi-pencil" style="cursor: pointer" onclick="editFood($(this).parent())"></i></span>')

            let calorieInput = $modalElement.parents("div.meal").find("input[name='calorie']")
            let proteinInput = $modalElement.parents("div.meal").find("input[name='protein']")
            let carbsInput = $modalElement.parents("div.meal").find("input[name='carbs']")
            let fatInput = $modalElement.parents("div.meal").find("input[name='fat']")

            calorieInput.val(roundNumber(parseFloat(calorieInput.val()) + calorie))
            proteinInput.val(roundNumber(parseFloat(proteinInput.val()) + protein))
            carbsInput.val(roundNumber(parseFloat(carbsInput.val()) + carbs))
            fatInput.val(roundNumber(parseFloat(fatInput.val()) + fat))

            operateProgressBars()
        }

        function requestAddSupplement() {
            let $modal = $("#supplement_modal")
            let $supplement = $modal.find("select[name='supplement_id']")
            let value = $modal.find("input[name='value']").val()
            let unitText = $modal.find("input[name='unit_text']").val()
            let text = CKEDITOR.instances.text.getData()

            if ($supplement.val() == null) {
                alertify.error('لطفا مکمل را وارد کنید.')
                return
            }

            if (unitText === '' && value === '') {
                alertify.error('لطفا مقدار واحد و یا مقدار دستی آن را وارد کنید.')
                return
            }

            if (unitText !== '' && value !== '') {
                alertify.error('لطفا یکی از مقادیر واحد و یا مقدار دستی را وارد کنید.')
                return
            }

            if (unitText === '') {
                if (value === '') {
                    alertify.error('لطفا مقدار را وارد کنید.')
                    return
                }

                if (isNaN(value)) {
                    alertify.error('لطفا مقدار را صحیح وارد کنید.')
                    return
                }

                value = parseFloat(value)

                if (value <= 0) {
                    alertify.error('لطفا مقدار را صحیح وارد کنید.')
                    return
                }
            }

            $modal.modal('toggle')


            // let calorie = (parseFloat($supplement.attr('data-calorie-value')) * value)
            // let protein = (parseFloat($supplement.attr('data-protein-value')) * value)
            // let fat = (parseFloat($supplement.attr('data-fat-value')) * value)
            // let carbs = (parseFloat($supplement.attr('data-carbs-value')) * value)
            // calorieSum += calorie
            // proteinSum += protein
            // fatSum += fat
            // carbsSum += carbs


            $("div.row.supplement table tbody").append(`
                    <tr data-unit-id="` + $modalSupplement.attr('data-unit-id') + `" data-unit-text="` + unitText + `" data-value="` + value + `" data-supplement-id="` + $modalSupplement.attr('data-supplement-id') + `" data-supplement-name="` + $modalSupplement.attr('data-supplement-name') + `" data-unit-name="` + $modalSupplement.attr('data-unit-name') + `">
    <td><b>` + '#' + `</b></td>
    <td>` + $modalSupplement.attr('data-supplement-name') + `</td>
    <td>` + (unitText === '' ? (value + ' ' + $modalSupplement.attr('data-unit-name')) : unitText) + `</td>
    <td>` + text + `</td>
    <td><a class="btn btn-primary mb-5 text-light"
href="javascript:void(0);"
           onclick="editSupplement($(this).parent().parent())">ویرایش</a>
    <a href="javascript:void(0);" class="btn btn-danger mb-5 text-light" onclick="$(this).parent().parent().remove()">حذف</a></td>
</tr>
`)

            // let calorieInput = $modalElement.parents("div.meal").find("input[name='calorie']")
            // let proteinInput = $modalElement.parents("div.meal").find("input[name='protein']")
            // let carbsInput = $modalElement.parents("div.meal").find("input[name='carbs']")
            // let fatInput = $modalElement.parents("div.meal").find("input[name='fat']")
            //
            // calorieInput.val(roundNumber(parseFloat(calorieInput.val()) + calorie))
            // proteinInput.val(roundNumber(parseFloat(proteinInput.val()) + protein))
            // carbsInput.val(roundNumber(parseFloat(carbsInput.val()) + carbs))
            // fatInput.val(roundNumber(parseFloat(fatInput.val()) + fat))
        }

        function requestEditFood(element) {
            let $modal = $("#foodModal")
            let $food = $modal.find("#select3")
            let value = $modal.find("input[name='value']").val()

            if ($food.val() == null) {
                alertify.error('لطفا غذا را وارد کنید.')
                return
            }

            if (value === '') {
                alertify.error('لطفا مقدار را وارد کنید.')
                return
            }

            if (isNaN(value)) {
                alertify.error('لطفا مقدار را صحیح وارد کنید.')
                return
            }

            value = parseFloat(value)

            if (value <= 0) {
                alertify.error('لطفا مقدار را صحیح وارد کنید.')
                return
            }

            $modal.modal('toggle')

            let calorie = (parseFloat($food.attr('data-calorie-value')) * value)
            let protein = (parseFloat($food.attr('data-protein-value')) * value)
            let fat = (parseFloat($food.attr('data-fat-value')) * value)
            let carbs = (parseFloat($food.attr('data-carbs-value')) * value)

            let oldCalorie = element.attr('data-calorie-value')
            let oldProtein = element.attr('data-protein-value')
            let oldFat = element.attr('data-fat-value')
            let oldCarbs = element.attr('data-carbs-value')

            calorieSum += calorie - oldCalorie
            proteinSum += protein - oldProtein
            fatSum += fat - oldFat
            carbsSum += carbs - oldCarbs

            element.attr('data-food-id', $food.attr('data-food-id'))
            element.attr('data-food-name', $food.attr('data-food-name'))
            element.attr('data-unit-name', $food.attr('data-unit-name'))
            element.attr('data-unit-id', $food.attr('data-unit-id'))
            element.attr('data-protein-value', protein)
            element.attr('data-fat-value', fat)
            element.attr('data-carbs-value', carbs)
            element.attr('data-calorie-value', calorie)
            element.attr('data-value', value)
            element.html((value + ' ' + $food.attr('data-unit-name') + ' ' + $food.attr("data-food-name")) + '<i class="mr-1 mdi mdi-delete" style="cursor: pointer" onclick="removeFood($(this).parent())"></i> <i class="mr-1 mdi mdi-pencil" style="cursor: pointer" onclick="editFood($(this).parent())"></i></span>')

            let calorieInput = $modalElement.parents("div.meal").find("input[name='calorie']")
            let proteinInput = $modalElement.parents("div.meal").find("input[name='protein']")

            calorieInput.val(parseFloat(calorieInput.val()) + (calorie - oldCalorie))
            proteinInput.val(parseFloat(proteinInput.val()) + protein - oldProtein)
        }

        function requestEditSupplement(element) {
            let $modal = $("#supplement_modal")
            let $supplement = $modal.find("select[name='supplement_id']")
            let value = $modal.find("input[name='value']").val()
            let unitText = $modal.find("input[name='unit_text']").val()
            let text = CKEDITOR.instances.text.getData()

            if ($supplement.val() == null) {
                alertify.error('لطفا مکمل را وارد کنید.')
                return
            }

            if (unitText === '' && value === '') {
                alertify.error('لطفا مقدار واحد و یا مقدار دستی آن را وارد کنید.')
                return
            }

            if (unitText !== '' && value !== '') {
                alertify.error('لطفا یکی از مقادیر واحد و یا مقدار دستی را وارد کنید.')
                return
            }

            if (unitText === '') {

                if (value === '') {
                    alertify.error('لطفا مقدار را وارد کنید.')
                    return
                }

                if (isNaN(value)) {
                    alertify.error('لطفا مقدار را صحیح وارد کنید.')
                    return
                }

                value = parseFloat(value)

                if (value <= 0) {
                    alertify.error('لطفا مقدار را صحیح وارد کنید.')
                    return
                }
            }

            $modal.modal('toggle')

            // let calorie = (parseFloat($supplement.attr('data-calorie-value')) * value)
            // let protein = (parseFloat($supplement.attr('data-protein-value')) * value)
            // let fat = (parseFloat($supplement.attr('data-fat-value')) * value)
            // let carbs = (parseFloat($supplement.attr('data-carbs-value')) * value)

            element.attr('data-supplement-id', $supplement.attr('data-supplement-id'))
            element.attr('data-supplement-name', $supplement.attr('data-supplement-name'))
            element.attr('data-unit-name', $supplement.attr('data-unit-name'))
            element.attr('data-unit-text', unitText)
            element.attr('data-unit-id', $supplement.attr('data-unit-id'))
            // element.attr('data-protein-value', protein)
            // element.attr('data-fat-value', fat)
            // element.attr('data-carbs-value', carbs)
            // element.attr('data-calorie-value', calorie)
            element.attr('data-value', value)

            element.html(`
    <td><b>` + '#' + `</b></td>
    <td>` + $supplement.attr('data-supplement-name') + `</td>
    <td>` + (unitText === '' ? (value + ' ' + $supplement.attr('data-unit-name')) : unitText) + `</td>
    <td>` + text + `</td>
    <td><a class="btn btn-primary mb-5 text-light"
href="javascript:void(0);"
           onclick="editSupplement($(this).parent().parent())">ویرایش</a>
    <a href="javascript:void(0);" class="btn btn-danger mb-5 text-light" onclick="$(this).parent().parent().remove()">حذف</a></td>
`)
        }


        function getDayNutrient(day, nutrient) {
            let sum = 0
            $("div.meal").each(function () {

                if ($(this).find("select[name='day']").val() === day) {
                    $(this).find(".foods span").each(function () {
                        sum += parseFloat($(this).attr('data-' + nutrient + '-value'))
                    })
                }
            })

            return sum
        }

        function showSuperNutritionBlock(element) {
            let parent = element.parents('div.meal')
            if (element.attr('data-show') === '1') {
                element.html('نمایش پیشنهاد درشت مغذی')
                element.attr('data-show', '0')
                parent.find(".super-nutrition-suggestion-section").hide()
            } else {
                element.html('مخفی کردن پیشنهاد درشت مغذی')
                element.attr('data-show', '1')
                parent.find(".super-nutrition-suggestion-section").show()
            }
        }

        function showSuggestion(element) {
            let parent = element.parents('div.meal')
            if (element.attr('data-show') === '1') {
                element.html('نمایش پیشنهاد')
                element.attr('data-show', '0')
                parent.find(".tag-section").hide()
            } else {
                element.html('مخفی کردن پیشنهاد')
                element.attr('data-show', '1')
                parent.find(".tag-section").show()
            }
        }

        function removeFood(element) {
            calorieSum -= parseFloat(element.attr('data-calorie-value'))
            proteinSum -= parseFloat(element.attr('data-protein-value'))
            carbsSum -= parseFloat(element.attr('data-carbs-value'))
            fatSum -= parseFloat(element.attr('data-fat-value'))

            let calorieInput = element.parents("div.meal").find("input[name='calorie']")
            let proteinInput = element.parents("div.meal").find("input[name='protein']")
            let carbsInput = element.parents("div.meal").find("input[name='carbs']")
            let fatInput = element.parents("div.meal").find("input[name='fat']")

            calorieInput.val(roundNumber(parseFloat(calorieInput.val()) - parseFloat(element.attr('data-calorie-value'))))
            proteinInput.val(roundNumber(parseFloat(proteinInput.val()) - parseFloat(element.attr('data-protein-value'))))
            carbsInput.val(roundNumber(parseFloat(carbsInput.val()) - parseFloat(element.attr('data-carbs-value'))))
            fatInput.val(roundNumber(parseFloat(fatInput.val()) - parseFloat(element.attr('data-fat-value'))))

            element.remove()
            operateProgressBars()
        }

        let $remainingCalorie = $("#remaining_calorie")
        let $remainingProtein = $("#remaining_protein")
        let $remainingFat = $("#remaining_fat")
        let $remainingCarbs = $("#remaining_carbs")

        let $saturdayCalorie = $("#saturday_calorie")
        let $sundayCalorie = $("#sunday_calorie")
        let $mondayCalorie = $("#monday_calorie")
        let $tuesdayCalorie = $("#tuesday_calorie")
        let $wednesdayCalorie = $("#wednesday_calorie")
        let $thursdayCalorie = $("#thursday_calorie")
        let $fridayCalorie = $("#friday_calorie")

        let $saturdayProtein = $("#saturday_protein")
        let $sundayProtein = $("#sunday_protein")
        let $mondayProtein = $("#monday_protein")
        let $tuesdayProtein = $("#tuesday_protein")
        let $wednesdayProtein = $("#wednesday_protein")
        let $thursdayProtein = $("#thursday_protein")
        let $fridayProtein = $("#friday_protein")


        function operateProgressBars() {
            let finalCalorie = parseFloat($finalCalorie.text())
            let finalProtein = parseFloat($finalProtein.text())
            let finalCarbs = parseFloat($finalCarbs.text())
            let finalFat = parseFloat($finalFat.text())

            $calorieProgressBar.css('width', ((calorieSum / (finalCalorie * 7)) * 100) + '%')
            $proteinProgressBar.css('width', ((proteinSum / (finalProtein * 7)) * 100) + '%')
            $carbsProgressBar.css('width', ((carbsSum / (finalCarbs * 7)) * 100) + '%')
            $fatProgressBar.css('width', ((fatSum / (finalFat * 7)) * 100) + '%')

            $remainingCalorie.text(numberFormat(((finalCalorie * 7) - calorieSum)))
                .css('color', (finalCalorie * 7) - calorieSum > 0 ? 'inherit' : '#f24734 ')

            $remainingProtein.text(numberFormat((finalProtein * 7) - proteinSum))
                .css('color', (finalProtein * 7) - proteinSum > 0 ? 'inherit' : '#f24734 ')

            $remainingCarbs.text(numberFormat((finalCarbs * 7) - carbsSum))
                .css('color', (finalCarbs * 7) - carbsSum > 0 ? 'inherit' : '#f24734 ')

            $remainingFat.text(numberFormat((finalFat * 7) - fatSum))
                .css('color', (finalFat * 7) - fatSum > 0 ? 'inherit' : '#f24734 ')

            $saturdayProtein.text(numberFormat(parseInt(finalProtein - getDayNutrient('saturday', 'protein'))))
                .css('color', finalProtein - getDayNutrient('saturday', 'protein') > 0 ? 'inherit' : '#f24734 ')

            $saturdayCalorie.text(numberFormat(parseInt(finalCalorie - getDayNutrient('saturday', 'calorie'))))
                .css('color', finalCalorie - getDayNutrient('saturday', 'calorie') > 0 ? 'inherit' : '#f24734 ')

            $sundayProtein.text(numberFormat(parseInt(finalProtein - getDayNutrient('sunday', 'protein'))))
                .css('color', finalProtein - getDayNutrient('sunday', 'protein') > 0 ? 'inherit' : '#f24734 ')

            $sundayCalorie.text(numberFormat(parseInt(finalCalorie - getDayNutrient('sunday', 'calorie'))))
                .css('color', finalCalorie - getDayNutrient('sunday', 'calorie') > 0 ? 'inherit' : '#f24734 ')

            $mondayProtein.text(numberFormat(parseInt(finalProtein - getDayNutrient('monday', 'protein'))))
                .css('color', finalProtein - getDayNutrient('monday', 'protein') > 0 ? 'inherit' : '#f24734 ')

            $mondayCalorie.text(numberFormat(parseInt(finalCalorie - getDayNutrient('monday', 'calorie'))))
                .css('color', finalCalorie - getDayNutrient('monday', 'calorie') > 0 ? 'inherit' : '#f24734 ')

            $tuesdayProtein.text(numberFormat(parseInt(finalProtein - getDayNutrient('tuesday', 'protein'))))
                .css('color', finalProtein - getDayNutrient('tuesday', 'protein') > 0 ? 'inherit' : '#f24734 ')

            $tuesdayCalorie.text(numberFormat(parseInt(finalCalorie - getDayNutrient('tuesday', 'calorie'))))
                .css('color', finalCalorie - getDayNutrient('tuesday', 'calorie') > 0 ? 'inherit' : '#f24734 ')

            $wednesdayProtein.text(numberFormat(parseInt(finalProtein - getDayNutrient('wednesday', 'protein'))))
                .css('color', finalProtein - getDayNutrient('wednesday', 'protein') > 0 ? 'inherit' : '#f24734 ')

            $wednesdayCalorie.text(numberFormat(parseInt(finalCalorie - getDayNutrient('wednesday', 'calorie'))))
                .css('color', finalCalorie - getDayNutrient('wednesday', 'calorie') > 0 ? 'inherit' : '#f24734 ')

            $thursdayProtein.text(numberFormat(parseInt(finalProtein - getDayNutrient('thursday', 'protein'))))
                .css('color', finalProtein - getDayNutrient('thursday', 'protein') > 0 ? 'inherit' : '#f24734 ')

            $thursdayCalorie.text(numberFormat(parseInt(finalCalorie - getDayNutrient('thursday', 'calorie'))))
                .css('color', finalCalorie - getDayNutrient('thursday', 'calorie') > 0 ? 'inherit' : '#f24734 ')

            $fridayProtein.text(numberFormat(parseInt(finalProtein - getDayNutrient('friday', 'protein'))))
                .css('color', finalProtein - getDayNutrient('friday', 'protein') > 0 ? 'inherit' : '#f24734 ')

            $fridayCalorie.text(numberFormat(parseInt(finalCalorie - getDayNutrient('friday', 'calorie'))))
                .css('color', finalCalorie - getDayNutrient('friday', 'calorie') > 0 ? 'inherit' : '#f24734 ')
        }

        function editFood(element) {
            let $modal = $("#foodModal")
            $modal.find("form").trigger('reset')

            $modalFood.append('<option selected="selected" value="' + element.attr('data-food-id') + '">' + element.attr('data-food-name') + '</option>')
                .trigger('change')

            $("#foodModal input[name='value']").val(element.attr('data-value'))
            $("#foodModal select[name='unit']").val(element.attr('data-unit-id'))

            $modalUnitCalorie.text(element.attr('data-calorie-value'))
            $modalUnitCarbs.text(element.attr('data-carbs-value'))
            $modalUnitFat.text(element.attr('data-fat-value'))
            $modalUnitProtein.text(element.attr('data-protein-value'))

            $modal.modal('toggle')
            $modalElement = element

            $modal.find("form")
                .unbind('submit')
                .on('submit', function (event) {
                    event.preventDefault()
                    requestEditFood(element)
                })
        }

        function editSupplement(element) {
            let $modal = $("#supplement_modal")
            $modal.find("form").trigger('reset')

            $modalSupplement.append('<option selected="selected" value="' + element.attr('data-supplement-id') + '">' + element.attr('data-supplement-name') + '</option>')
                .trigger('change')

            $("#supplement_modal input[name='value']").val(element.attr('data-value'))
            $("#supplement_modal input[name='unit_text']").val(element.attr('data-unit-text'))
            $("#supplement_modal select[name='unit']").val(element.attr('data-unit-id'))
            CKEDITOR.instances.text.setData(element.find("td:eq(3)").html())

            // $modalUnitCalorie.text(element.attr('data-calorie-value'))
            // $modalUnitCarbs.text(element.attr('data-carbs-value'))
            // $modalUnitFat.text(element.attr('data-fat-value'))
            // $modalUnitProtein.text(element.attr('data-protein-value'))

            $modal.modal('toggle')

            $modal.find("form")
                .unbind('submit')
                .on('submit', function (event) {
                    event.preventDefault()
                    requestEditSupplement(element)
                })
        }

        function addFood(element, food = null) {
            let $modal = $("#foodModal")
            $("#foodModal #unit_section").hide()
            $modal.find("form").trigger('reset')

            $modalUnitCalorie.text('')
            $modalUnitCarbs.text('')
            $modalUnitFat.text('')
            $modalUnitProtein.text('')

            $modal.modal('toggle')
            $modalElement = element

            $modal.find("form")
                .unbind('submit')
                .on('submit', function (event) {
                    event.preventDefault()
                    requestAddFood()
                })

            if (food !== null) {
                $modalFood.append('<option selected="selected" value="' + food['id'] + '">' + food['name_fa'] + '</option>')
                    .trigger('change')


                if (food['unit_value'] !== undefined) {
                    let val = $("#foodModal input[name='value']")
                    val.val(food['unit_value'])
                    $("#foodModal select[name='unit']").val(food['default_unit']['id'])

                    $modalFood.attr('data-protein-value', food['default_unit']['real_protein'])
                    $modalFood.attr('data-carbs-value', food['default_unit']['real_carbs'])
                    $modalFood.attr('data-fat-value', food['default_unit']['real_fat'])
                    $modalFood.attr('data-calorie-value', food['default_unit']['real_calorie'])

                    val.trigger('keyup')
                }

            } else
                $modalFood.html('')
        }

        function addSupplement(element, supplement = null) {
            let $modal = $("#supplement_modal")
            $("#supplement_modal #unit_section").hide()
            $modal.find("form").trigger('reset')
            CKEDITOR.instances.text.setData('')

            $modal.modal('toggle')
            $modalElement = element

            $modal.find("form")
                .unbind('submit')
                .on('submit', function (event) {
                    event.preventDefault()
                    requestAddSupplement()
                })

            if (supplement !== null) {
                $modalSupplement.append('<option selected="selected" value="' + supplement['id'] + '">' + supplement['name_fa'] + '</option>')
                    .trigger('change')


                if (supplement['unit_value'] !== undefined) {
                    let val = $("#supplement_modal input[name='value']")
                    val.val(supplement['unit_value'])
                    $("#supplement_modal select[name='unit']").val(supplement['default_unit']['id'])
                }
            } else
                $modalSupplement.html('')
        }

        function randomTags(element, id) {
            let meal = element.parents("div.meal")

            let calorie = meal.find("input[name='calorie_suggestion']")
            let carbs = meal.find("input[name='carbs_suggestion']")
            let protein = meal.find("input[name='protein_suggestion']")
            let fat = meal.find("input[name='fat_suggestion']")

            let calorieValue = calorie.val()
            let carbsValue = carbs.val()
            let proteinValue = protein.val()
            let fatValue = fat.val()

            let isUnitSuggestion = meal.find(".super-nutrition-suggestion-button").attr('data-show') === '1'

            if (isUnitSuggestion && (isNaN(calorieValue) || isNaN(carbsValue) || isNaN(proteinValue) || isNaN(fatValue) || calorieValue === '0')) {
                alertify.error('لطفا مقادیر پیشنهاد را به درستی وارد کنید.');
                return
            }

            let finalCalorie = calorieValue - parseFloat(meal.find("input[name='calorie']").val())
            let finalCarbs = carbsValue - parseFloat(meal.find("input[name='carbs']").val())
            let finalProtein = proteinValue - parseFloat(meal.find("input[name='protein']").val())
            let finalFat = proteinValue - parseFloat(meal.find("input[name='fat']").val())

            $.ajax({
                type: "post",
                url: '{{ Route('admin_diet_program_action', ['action' => 'tags']) }}',
                dataType: "json",
                data: {
                    '_token': $("meta[name='csrf-token']").attr('content'),
                    'id': id,
                    'calorie': !isUnitSuggestion ? null : finalCalorie,
                    'carbs': !isUnitSuggestion ? null : finalCarbs,
                    'protein': !isUnitSuggestion ? null : finalProtein,
                    'fat': !isUnitSuggestion ? null : finalFat,
                },
                success: function (response) {
                    if (response['success']) {
                        let $html = ''
                        for (let i = 0; i < response['foods'].length; i++) {
                            let $food = response['foods'][i]
                            let anchor = $food['unit_value'] === undefined ? $food['tag_fa'] : ($food['unit_value'] + ' ' + $food['default_unit']['name_fa'] + ' ' + $food['name_fa']).replace(' ', '_')
                            $html += "<button class='btn btn-rounded btn-light' onclick='addFood($(this), " + JSON.stringify($food) + ")'>" + anchor + "</button>"
                        }

                        meal.find(".tags").html($html)

                    } else
                        alertify.error(response['message'])
                },
                error: function () {
                    alertify.error('خطا رخ داد.')
                }
            })
        }

        function addMeal() {
            let $html = '{!! str_replace("\n","\\n",view('admin.nutrition.components.single_add_meal', compact('meals'))->render()) !!}'
            $meals.append($html)
            handleMeal()
        }

        function addMeals() {

            alertify.confirm("آیا از افزوده شدن چند وعده از پیش تعیین شده مطمئن هستید؟", function (ev) {
                changeMeal('saturday')
                changeMeal('sunday')
                changeMeal('monday')
                changeMeal('tuesday')
                changeMeal('wednesday')
                changeMeal('thursday')
                changeMeal('friday')

                handleMeal()
                ev.preventDefault()
            }, function (ev) {
                ev.preventDefault()
            })
        }

        function changeMeal(day) {
            addProMeal(day, 'breakfast', 'صبحانه', 'Breakfast')
            addProMeal(day, 'snack', '10 صبح', "10:00' clock")
            addProMeal(day, 'lunch', 'ناهار', "Lunch")
            addProMeal(day, 'snack', '5 عصر', "17:00' clock")
            addProMeal(day, 'snack', '7 عصر', "19:00' clock")
            addProMeal(day, 'dinner', 'شام', "Dinner")
            addProMeal(day, 'snack', 'قبل خواب', "Before Going to Bed")
        }

        function addProMeal(day, meal, nameFa, nameEn) {
            let $html = '{!! str_replace("\n","\\n",view('admin.nutrition.components.single_add_meal', compact('meals'))->render()) !!}'
            $meals.append($html)
            let element = $meals.find('div.row.meal:last-child')
            element.find("select[name='meal']").val(meal)
            element.find("select[name='day']").val(day)
            element.find("input[name='name_fa']").val(nameFa)
            element.find("input[name='name_en']").val(nameEn)
        }

        function removeMeal(element) {
            element = element.parents("div.meal")

            alertify.confirm("از حذف این وعده غذایی مطمئنید؟", function (ev) {
                element.find(".foods span").each(function () {
                    calorieSum -= parseFloat($(this).attr('data-calorie-value'))
                    proteinSum -= parseFloat($(this).attr('data-protein-value'))
                    carbsSum -= parseFloat($(this).attr('data-carbs-value'))
                    fatSum -= parseFloat($(this).attr('data-fat-value'))
                    operateProgressBars()
                })
                element.remove()
                ev.preventDefault()
            }, function (ev) {
                ev.preventDefault()
            })
        }

        function getNormalCalorie() {
            return parseFloat($activity.attr('data-content')) * parseFloat($bmr.text())
        }

        function writeInit(program = null) {
            let calorieValue
            let proteinValue
            let carbsValue
            let fatValue

            let normalCalorie = getNormalCalorie()
            let proteinDivider = parseInt(normalCalorie / 4.0)
            let carbsDivider = parseInt(normalCalorie / 4.0)
            let fatDivider = parseInt(normalCalorie / 9.0)

            if (program == null) {
                calorieValue = normalCalorie
                proteinValue = (((37.5 / 100.0) * normalCalorie) / 4.0).toFixed()
                carbsValue = (((37.5 / 100.0) * normalCalorie) / 4.0).toFixed()
                fatValue = (((25 / 100.0) * normalCalorie) / 9.0).toFixed()
            } else {
                calorieValue = program.calorie
                proteinValue = program.protein
                carbsValue = program.carbs
                fatValue = program.fat
            }

            $finalCalorieSeekbar.update({
                from: calorieValue,
                to: calorieValue,
                max: 5000
            });

            $calorieEditText.val(calorieValue.toFixed())

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


            if (program == null)
                $calorieEditText.trigger("change")

            writeInfo()
            operateProgressBars()
        }

        function deleteItem(id) {
            alertify.confirm("در صورت تایید، این آیتم حذف خواهد شد.", function (ev) {
                ev.preventDefault();
                $.ajax({
                    type: "post",
                    url: '{{ route('admin_food_manage') }}',
                    dataType: "json",
                    data: {
                        '_token': $("meta[name='csrf-token']").attr('content'),
                        'id': id,
                        'action': 'delete'
                    },
                    success: function (response) {
                        if (response['success']) {
                            alertify.success(response['message'])
                            setTimeout(function () {
                                window.location.href = '{{ route('admin_food_manage') }}'
                            }, 1500)
                        } else
                            alertify.error(response['message'])
                    },
                    error: function () {
                        alertify.error('خطا رخ داد.')
                    }
                })
            }, function (ev) {
                ev.preventDefault()
            });
        }

        $("form#main").on('submit', function (e) {
            e.preventDefault()
        })

        $("#send_user_modal form").on('submit', function (e) {
            e.preventDefault();
            let userId = $(this).find("select[name='user']").val()

            if (userId == null || userId === '') {
                alertify.error('لطفا کاربر را انتخاب کنید.')
                return
            }
            submit(userId)
            $("#send_user_modal").modal('hide')
        })

        function initEdit(program) {
            writeInit(program)

            console.log(program)

            jQuery.each(program.supplements, function (i, supplement) {
                $("div.row.supplement table tbody").append(`
                    <tr data-unit-id="` + (supplement['unit_id'] == null ? '' : supplement['unit']['id']) + `" data-value="` + (supplement['value'] === null ? '' : supplement['value']) + `" data-unit-text="` + (supplement['unit_text'] == null ? '' : supplement['unit_text']) + `" data-supplement-id="` + supplement['supplement_id'] + `" data-supplement-name="` + supplement['supplement']['name_fa'] + `" data-unit-name="` + (supplement['unit_id'] == null ? '' : supplement['unit']['name_fa']) + `">
    <td><b>` + '#' + `</b></td>
    <td>` + supplement['supplement']['name_fa'] + `</td>
    <td>` + (supplement['unit_text'] != null ? supplement['unit_text'] : (supplement['value'] + ' ' + (supplement['unit'] == null ? '' : supplement['unit']['name_fa']))) + `</td>
    <td>` + supplement['text'] + `</td>
    <td><a class="btn btn-primary mb-5 text-light"
href="javascript:void(0);"
           onclick="editSupplement($(this).parent().parent())">ویرایش</a>
    <a href="javascript:void(0);" class="btn btn-danger mb-5 text-light" onclick="$(this).parent().parent().remove()">حذف</a></td>
</tr>
`)
            })

            jQuery.each(program.days, function (i, day) {

                jQuery.each(day.meals, function (i, meal) {
                    addMeal()
                    let mealDom = $meals.find("div.meal:last-child")
                    let realMealName = meal.name_en.replace(' ', '-').toLowerCase();

                    if (realMealName!== 'breakfast' && realMealName!== 'lunch' && realMealName!== 'dinner')
                        realMealName = 'snack'

                    mealDom.find("select[name='meal']").val(realMealName)
                        .trigger("change")
                    mealDom.find("select[name='day']").val(day.day)

                    mealDom.find("input[name='name_fa']").val(meal['name_fa'])
                    mealDom.find("input[name='name_en']").val(meal['name_en'])

                    jQuery.each(meal.items, function (i, item) {

                        let calorie = item.unit['real_calorie'] * item.value
                        let protein = item.unit['real_protein'] * item.value
                        let fat = item.unit['real_fat'] * item.value
                        let carbs = item.unit['real_carbs'] * item.value

                        calorieSum += calorie
                        proteinSum += protein
                        fatSum += fat
                        carbsSum += carbs

                        let layout = mealDom.find("div.foods")
                        layout.append('<span class="btn btn-primary m-1" ' +
                            'data-food-name="' + item['food']['name_fa'] + '" ' +
                            'data-unit-name="' + item['unit']['name_fa'] + '" ' +
                            'data-food-id="' + item.food_id + '" ' +
                            'data-unit-id="' + item.unit_id + '"  ' +
                            'data-protein-value="' + protein + '"  ' +
                            'data-fat-value="' + fat + '"  ' +
                            'data-carbs-value="' + carbs + '"  ' +
                            'data-calorie-value="' + calorie + '"  ' +
                            'data-value="' + item.value + '"' +
                            '>' + (item.value + ' ' + item.unit.name_fa + ' ' + item.food.name_fa) + '<i class="mr-1 mdi mdi-delete" style="cursor: pointer" onclick="removeFood($(this).parent())"></i><i class="mr-1 mdi mdi-pencil" style="cursor: pointer" onclick="editFood($(this).parent())"></i></span>')

                        let calorieInput = mealDom.find("input[name='calorie']")
                        let proteinInput = mealDom.find("input[name='protein']")
                        let fatInput = mealDom.find("input[name='fat']")
                        let carbsInput = mealDom.find("input[name='carbs']")

                        calorieInput.val(numberFormat(parseFloat(calorieInput.val()) + calorie))
                        proteinInput.val(numberFormat(parseFloat(proteinInput.val()) + protein))
                        carbsInput.val(numberFormat(parseFloat(carbsInput.val()) + carbs))
                        fatInput.val(numberFormat(parseFloat(fatInput.val()) + fat))

                        $("textarea[name='note']").val(program.note)
                    })
                })
            });

            operateProgressBars()
        }

        @if($type == 'edit')
        $(document).ready(function () {

            function startEdit(program) {
                $("#select2").append('<option selected="selected" value="' + program['user_id'] + '">' + program.user['first_name'] + ' ' + program.user['last_name'] + ' (' + (
                    program.user['contact']
                ) + ')' + '</option>')
                    .trigger('change')

                $("#send_user_modal select[name='user']").append('<option selected="selected" value="' + program['user_id'] + '">' + program.user['first_name'] + ' ' + program.user['last_name'] + ' (' + (
                    program.user['contact']
                ) + ')' + '</option>')
                    .trigger('change')
            }

            startEdit({!! $program !!})
        })
        @endif


        let formSubmitting = false;
        let setFormSubmitting = function () {
            formSubmitting = true;
        };

        window.onload = function () {
            window.addEventListener("beforeunload", function (e) {
                if (formSubmitting) {
                    return undefined;
                }

                let confirmationMessage = 'بنظر می آید شما میخواهید این صفحه را ترک کنید. آیا از این کار مطمئن هستید؟';

                (e || window.event).returnValue = confirmationMessage; //Gecko + IE
                return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
            });
        };

    </script>
@endsection
@include('admin.theme.master')
