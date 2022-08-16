<div class="card card-default">
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">عنوان فارسی</th>
            <th scope="col">عنوان انگلیسی</th>
            <th scope="col">مقدار (گرم)</th>
            <th scope="col" width="30%">عملیات</th>
        </tr>
        </thead>
        <tbody>

        @php
            $p = Request()->has('page') ? Request()->page : 1;
        @endphp
        @foreach($materials as $material)
            <tr>
                <th scope="row">{{ $loop->iteration + (($p - 1) * $materials->perPage()) }}</th>
                <td>{{ $material->name_fa }}</td>
                <td>{{ $material->name_en }}</td>
                <td>{{ $material->value }}</td>
                <td>
                    <button class="mb-1 btn btn-outline-dark" type="button"
                            onclick="editItem({{ $material }})">
                        ویرایش
                    </button>
                    <button class="mb-1 btn btn-outline-danger" type="button"
                            onclick="confirmDelete({{ $material->id }})">حذف
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="mt-2">{{ $materials->render() }}</div>
