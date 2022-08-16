<div class="card card-default">
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">نام فارسی</th>
            <th scope="col">مقدار فارسی</th>
            <th scope="col">نام انگلیسی</th>
            <th scope="col">مقدار انگلیسی</th>
            <th scope="col" width="30%">عملیات</th>
        </tr>
        </thead>
        <tbody>

        @php
            $p = Request()->has('page') ? Request()->page : 1;
        @endphp

        @foreach($ingredients as $ingredient)
            <tr>
                <th scope="row">{{ $loop->iteration + (($p - 1) * $ingredients->perPage()) }}</th>
                <td>{{ $ingredient->name_fa }}</td>
                <td>{{ $ingredient->value_fa }} </td>
                <td dir="ltr">{{ $ingredient->name_en }}</td>
                <td dir="ltr">{{ $ingredient->value_en }} </td>
                <td>
                    <button class="mb-1 btn btn-outline-dark" type="button" onclick="editItem({{ $ingredient }})">
                        ویرایش
                    </button>
                    <button class="mb-1 btn btn-outline-danger" type="button"
                            onclick="confirmDelete({{ $ingredient->id }})">حذف
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="mt-2">{{ $ingredients->render() }}</div>
