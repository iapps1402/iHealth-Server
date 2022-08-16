<div class="card card-default">
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">تغییر</th>
            <th scope="col" width="30%">عملیات</th>
        </tr>
        </thead>
        <tbody>

        @php
            $p = Request()->has('page') ? Request()->page : 1;
        @endphp
        @foreach($changes as $change)
            <tr>
                <th scope="row">{{ $loop->iteration + (($p - 1) * $changes->perPage()) }}</th>
                <td>{{ $change->text_fa }}</td>
                <td>
                    <button class="mb-1 btn btn-outline-secondary" type="button"
                            onclick="window.location.href='{{ route('admin_application_version_change_edit', ['id' => $change->id, 'application_id' => $applicationId, 'version_id' => $versionId]) }}'">
                        ویرایش
                    </button>

                    <button class="mb-1 btn btn-outline-danger" type="button"
                            onclick="confirmDelete({{ $change->id }})">حذف
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="mt-2">{{ $changes->render() }}</div>
