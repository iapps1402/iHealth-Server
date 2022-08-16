<div class="card card-default">
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">نام</th>
            <th scope="col">شماره</th>
            <th scope="col">تعداد تغییرات</th>
            <th scope="col" width="30%">عملیات</th>
        </tr>
        </thead>
        <tbody>

        @php
            $p = Request()->has('page') ? Request()->page : 1;
        @endphp
        @foreach($versions as $version)
            <tr>
                <th scope="row">{{ $loop->iteration + (($p - 1) * $versions->perPage()) }}</th>
                <td>
                    @if($version->application->current_id == $version->id)
                        <i style="font-size: 15pt" class="text-success mdi mdi-chevron-up"></i>
                    @endif

                    @if($version->application->min_id == $version->id)
                        <i style="font-size: 15pt;" class="text-danger mdi mdi-chevron-down"></i>
                    @endif

                    {{ $version->name }}
                </td>
                <td>{{ $version->number }}</td>
                <td>{{ $version->changes_count }}</td>
                <td>
                    <button class="mb-1 btn btn-outline-primary" type="button"
                            onclick="window.location.href='{{ route('admin_application_version_change_manage', ['application_id' => $applicationId, 'version_id' => $version->id]) }}'">
                        تغییرات
                    </button>

                    <button class="mb-1 btn btn-outline-secondary" type="button"
                            onclick="window.location.href='{{ route('admin_application_version_edit', ['id' => $version->id, 'application_id' => $applicationId]) }}'">
                        ویرایش
                    </button>

                    @if($version->application->current_id != $version->id)
                        <button class="mb-1 btn btn-outline-dark" type="button"
                                onclick="currentVersion({{ $version->id }})">
                            نسخه کنونی
                        </button>
                    @endif

                    @if($version->application->min_id != $version->id)
                        <button class="mb-1 btn btn-outline-dark" type="button"
                                onclick="minVersion({{ $version->id }})">
                            کمترین نسخه
                        </button>
                    @endif

                    <button class="mb-1 btn btn-outline-danger" type="button"
                            onclick="confirmDelete({{ $version->id }})">حذف
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="mt-2">{{ $versions->render() }}</div>
