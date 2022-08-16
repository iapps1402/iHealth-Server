<div class="card card-default">
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">نام فارسی</th>
            <th scope="col">نام انگلیسی</th>
            <th scope="col">نسخه فعلی</th>
            <th scope="col">کمترین نسخه</th>
            <th scope="col" width="30%">عملیات</th>
        </tr>
        </thead>
        <tbody>

        @php
            $p = Request()->has('page') ? Request()->page : 1;
        @endphp
        @foreach($applications as $application)
            <tr>
                <th scope="row">{{ $loop->iteration + (($p - 1) * $applications->perPage()) }}</th>
                <td>{{ $application->name_fa }}</td>
                <td>{{ $application->name_en }}</td>
                <td>{{ $application->current_version_name }}</td>
                <td>{{ $application->min_version_name }}</td>
                <td>
                    <button class="mb-1 btn btn-outline-primary" type="button"
                            onclick="window.location.href='{{ route('admin_application_version_manage', ['application_id' => $application->id]) }}'">نسخه ها
                    </button>

                    <button class="mb-1 btn btn-outline-secondary" type="button"
                            onclick="window.location.href='{{ route('admin_application_edit', ['id' => $application->id]) }}'">
                        ویرایش
                    </button>

                    <button class="mb-1 btn btn-outline-danger" type="button"
                            onclick="confirmDelete({{ $application->id }})">حذف
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="mt-2">{{ $applications->render() }}</div>
