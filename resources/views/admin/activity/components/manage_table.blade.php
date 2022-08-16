<div class="card card-default">
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">نام فارسی</th>
            <th scope="col">نام انگلیسی</th>
            <th scope="col">MET</th>
            <th scope="col" width="30%">عملیات</th>
        </tr>
        </thead>
        <tbody>

        @php
        $p = Request()->has('page') ? Request()->page : 1;
        @endphp
        @foreach($activities as $activity)
            <tr>
                <th scope="row">{{ $loop->iteration + (($p - 1) * $activities->perPage()) }}</th>
                <td>{{ $activity->name_fa }}</td>
                <td>{{ $activity->name_en }}</td>
                <td>{{ $activity->met }}</td>
                <td>
                    <button class="mb-1 btn btn-outline-dark" type="button"
                            onclick="window.location.href= '{{ Route('admin_activity_edit',['id' => $activity->id]) }}'">
                        ویرایش
                    </button>
                    <button class="mb-1 btn btn-outline-danger" type="button"
                            onclick="confirmDelete({{ $activity->id }})">حذف
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="mt-2">{{ $activities->render() }}</div>
