<div class="card card-default">
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">عنوان فارسی</th>
            <th scope="col">عنوان انگلیسی</th>
            <th scope="col" width="30%">عملیات</th>
        </tr>
        </thead>
        <tbody>

        @php
            $p = Request()->has('page') ? Request()->page : 1;
        @endphp

        @foreach($categories as $category)
            <tr>
                <th scope="row">{{ $loop->iteration + (($p - 1) * $categories->perPage()) }}</th>
                <td>{{ $category->name_fa }}</td>
                <td>{{ $category->name_en }} </td>
                <td>
                    <button class="mb-1 btn btn-outline-dark" type="button" onclick="editItem({{ $category }})">ویرایش</button>
                    <button class="mb-1 btn btn-outline-danger" type="button" onclick="confirmDelete({{ $category->id }})">حذف</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="mt-2">{{ $categories->render() }}</div>
