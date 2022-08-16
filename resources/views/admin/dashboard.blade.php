@section('container')

    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary mini-stat text-white">
                <div class="p-3 mini-stat-desc">
                    <div class="clearfix">
                        <h6 class="text-uppercase mt-0 float-left text-white-50">کاربران</h6>
                        <h4 class="mb-3 mt-0 float-right">{{ number_format($usersCount) }}</h4>
                    </div>

                </div>
                <div class="p-3">
                    <div class="float-right">
                        <span class="text-white-50"><i class="mdi mdi-account-outline h5"></i></span>
                    </div>
                    <p class="font-14 m-0">آنلاین : {{ number_format($onlineUsersCount) }}</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-info mini-stat text-white">
                <div class="p-3 mini-stat-desc">
                    <div class="clearfix">
                        <h6 class="text-uppercase mt-0 float-left text-white-50">بازخورد کاربران اپلیکیشن</h6>
                        <h4 class="mb-3 mt-0 float-right">{{ number_format($appRatingsCount) }}</h4>
                    </div>
                </div>
                <div class="p-3">
                    <div class="float-right">
                        <a href="#" class="text-white-50"><i class="mdi mdi-buffer h5"></i></a>
                    </div>
                    <p class="font-14 m-0">میانگین نتایج : {{ number_format($appRatingsAvg) }}</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-pink mini-stat text-white">
                <div class="p-3 mini-stat-desc">
                    <div class="clearfix">
                        <h6 class="text-uppercase mt-0 float-left text-white-50">غذاها</h6>
                        <h4 class="mb-3 mt-0 float-right">{{ number_format($foodsCount) }}</h4>
                    </div>
                </div>
                <div class="p-3">
                    <div class="float-right">
                        <i class="mdi mdi-food-apple-outline h5"></i>
                    </div>
                    <p class="font-14 m-0">دسته بندی ها : {{ number_format($foodCategoriesCount) }}</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-success mini-stat text-white">
                <div class="p-3 mini-stat-desc">
                    <div class="clearfix">
                        <h6 class="text-uppercase mt-0 float-left text-white-50">برنامه تغذیه</h6>
                        <h4 class="mb-3 mt-0 float-right">{{ number_format($dietProgramsCount) }}</h4>
                    </div>
                </div>
                <div class="p-3">
                    <div class="float-right">
                        <i class="mdi mdi-silverware-spoon h5"></i>
                    </div>
                    <p class="font-14 m-0">آخرین : {{ $lastDietProgram }}</p>
                </div>
            </div>
        </div>
    </div>


@endsection
@include('admin.theme.master')
