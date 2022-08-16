<div class="card card-default">
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">تصویر</th>
            <th scope="col">نام فارسی</th>
            <th scope="col">نام انگلیسی</th>
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
                <td><img src="{{ URL::to($category->picture->thumbnail->absolute_path) }}" width="80" height="40"/>
                </td>
                <td>{{ $category->name_fa }}</td>
                <td>{{ $category->name_en }} </td>
                <td>

                    <button class="mb-1 btn btn-outline-dark" type="button"
                            onclick="window.location.href='{{ Route('admin_food_category_edit', ['id' => $category->id]) }}'">
                        ویرایش
                    </button>

                    <button class="mb-1 btn btn-outline-danger" type="button"
                            onclick="confirmDelete({{ $category->id }})">حذف
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="mt-2">{{ $categories->render() }}</div>
