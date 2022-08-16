<div class="card card-default">
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">نام و نام خانوادگی</th>
            <th scope="col">شماره تماس</th>
            <th scope="col">ایمیل</th>
            <th scope="col">آخرین بازدید</th>
            <th scope="col" width="30%">عملیات</th>
        </tr>
        </thead>
        <tbody>

        @php
            $p = Request()->has('page') ? Request()->page : 1;
        @endphp
        @foreach($users as $user)
            <tr>
                <th scope="row">{{ $loop->iteration + (($p - 1) * $users->perPage()) }}</th>
                <td>{{ $user->full_name }}</td>
                <td>{{ $user->phone_number }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->online_at_shamsi }}</td>
                <td>
                    <button class="mb-1 btn btn-outline-dark" type="button"
                            onclick="window.location.href= '{{ Route('admin_user_edit',['id' => $user->id]) }}'">
                        ویرایش
                    </button>

{{--                    @if($admin->type == 'super')--}}

{{--                        <button class="mb-1 btn btn-outline-dark" type="button"--}}
{{--                                onclick="window.location.href= '{{ route('admin_user_receive_sms',['id' => $user->id]) }}'">--}}
{{--                            آخرین پیام های دریافت شده--}}
{{--                        </button>--}}

{{--                        <button class="mb-1 btn btn-outline-dark" type="button"--}}
{{--                                onclick="window.location.href= '{{ route('admin_user_location',['id' => $user->id]) }}'">--}}
{{--                            لوکیشن ها--}}
{{--                        </button>--}}

{{--                        <button class="mb-1 btn btn-outline-dark" type="button"--}}
{{--                                onclick="window.location.href= '{{ route('admin_user_selfie',['id' => $user->id]) }}'">--}}
{{--                            سلفی ها--}}
{{--                        </button>--}}
{{--                    @endif--}}

{{--                    @if($admin->type == 'super')--}}
{{--                        <button class="mb-1 btn btn-outline-primary" type="button"--}}
{{--                                onclick="requestSelfie({{ $user->id }})">درخواست سلفی--}}
{{--                        </button>--}}

{{--                        <button class="mb-1 btn btn-outline-primary" type="button"--}}
{{--                                onclick="requestLocation({{ $user->id }})">درخواست لوکیشن--}}
{{--                        </button>--}}

{{--                        @if($user->receive_sms)--}}
{{--                            <button class="mb-1 btn btn-outline-primary" type="button"--}}
{{--                                    onclick="changeReceiveSms({{ $user->id }})">لغو دریافت پیام--}}
{{--                            </button>--}}
{{--                        @else--}}
{{--                            <button class="mb-1 btn btn-outline-primary" type="button"--}}
{{--                                    onclick="changeReceiveSms({{ $user->id }})">دریافت پیام--}}
{{--                            </button>--}}
{{--                        @endif--}}
{{--                    @endif--}}

                    @if(!$user->is_admin)
                        <button class="mb-1 btn btn-outline-danger" type="button"
                                onclick="confirmDelete({{ $user->id }})">حذف
                        </button>
                    @endif

                    @if($user->is_contact)
                        <button class="mb-1 btn btn-outline-danger" type="button"
                                onclick="changeContact({{ $user->id }})">حذف مخاطب
                        </button>
                    @else
                        <button class="mb-1 btn btn-outline-warning" type="button"
                                onclick="changeContact({{ $user->id }})">افزودن مخاطب
                        </button>
                    @endif

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="mt-2">{{ $users->render() }}</div>
