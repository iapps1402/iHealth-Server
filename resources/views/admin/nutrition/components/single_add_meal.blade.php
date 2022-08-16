<div class="row meal">
    <div class="col-12">
        <div class="card card-default">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="page-title m-0"></h4>
                    </div>
                    <div class="col-md-12">
                        <div class="float-right d-none d-md-block">
                            <button class="btn btn-outline-primary" onclick="addFood($(this))">افزودن غذا</button>
                            <button class="btn btn-outline-pink" onclick="showSuggestion($(this))" data-show="1">مخفی کردن پیشنهاد</button>
                            <button class="btn btn-outline-secondary super-nutrition-suggestion-button" onclick="showSuperNutritionBlock($(this))" data-show="0">نمایش پیشنهاد درشت مغذی</button>
                            <button class="btn btn-outline-danger" onclick="removeMeal($(this))">حذف</button>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
            </div>

            <div class="card-body">

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>انتخاب وعده:</label>
                            <select class="form-control" name="meal">
                                <option disabled selected>انتخاب وعده</option>
                                <option value="breakfast">صبحانه</option>
                                <option value="lunch">ناهار</option>
                                <option value="dinner">شام</option>
                                <option value="snack">میان وعده</option>
                                <option value="before-workout">قبل تمرین</option>
                                <option value="after-workout">بعد تمرین</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <label>انتخاب روز هفته:</label>
                            <select class="form-control" name="day">
                                <option value="saturday">شنبه</option>
                                <option value="sunday">یکشنبه</option>
                                <option value="monday">دوشنبه</option>
                                <option value="tuesday">سه شنبه</option>
                                <option value="wednesday">چهارشنبه</option>
                                <option value="thursday">پنج شنبه</option>
                                <option value="friday">جمعه</option>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>نام فارسی وعده غذایی:</label>
                            <input class="form-control" type="text" name="name_fa"
                                   required>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <label>نام انگلیسی وعده غذایی:</label>
                            <input class="form-control" type="text" name="name_en"
                                   required>
                        </div>
                    </div>
                </div>

                <div class="card super-nutrition-suggestion-section" style="display: none">
                    <div class="card-body">
                        <div class="card-title">پیشنهاد درشت مغذی های این وعده</div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>پروتئین:</label>
                                    <input class="form-control" type="text" name="protein_suggestion"
                                           value="0">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label>کربوهیدرات:</label>
                                    <input class="form-control" type="text" name="carbs_suggestion"
                                           value="0">
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>چربی:</label>
                                    <input class="form-control" type="text" name="fat_suggestion"
                                           value="0">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label>کالری:</label>
                                    <input class="form-control" type="text" name="calorie_suggestion"
                                           value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="card">
                    <div class="card-body">
                        <div class="card-title">مقدار درشت مغذی های غذاهای ثبت شده این وعده</div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>پروتئین:</label>
                                    <input class="form-control" type="text" name="protein"
                                           style="cursor: default"
                                           value="0"
                                           readonly>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label>کربوهیدرات:</label>
                                    <input class="form-control" type="text" name="carbs"
                                           style="cursor: default"
                                           value="0"
                                           readonly>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>چربی:</label>
                                    <input class="form-control" type="text" name="fat"
                                           style="cursor: default"
                                           value="0"
                                           readonly>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label>کالری:</label>
                                    <input class="form-control" type="text" name="calorie"
                                           style="cursor: default"
                                           value="0"
                                           readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">

                        <div class="card card-default">
                            <div class="card-header">غذاهای ثبت شده
                            </div>

                            <div class="card-body">
                                <div class="foods"></div>
                            </div>
                        </div>

                    </div>


                    <div class="col-12 mt-4 tag-section">

                        <div class="card card-default">
                            <div class="card-header">پیشنهاد غذا
                            </div>

                            <div class="card-body">

                                <div class="btn-group" data-toggle="buttons">
                                    @foreach($meals as $meal)
                                        <button class="btn btn-info"
                                                onclick="randomTags($(this), {{ $meal->id }})">{{ $meal->name_fa }}</button>
                                    @endforeach
                                </div>

                                <div class="tags mt-3"></div>

                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

</div>
