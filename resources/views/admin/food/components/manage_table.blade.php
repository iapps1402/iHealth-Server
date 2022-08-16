<div class="card card-default">
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">تصویر</th>
            <th scope="col">عنوان فارسی</th>
            <th scope="col">عنوان انگلیسی</th>
            <th scope="col" width="30%">عملیات</th>
        </tr>
        </thead>
        <tbody>

        @php
            $p = Request()->has('page') ? Request()->page : 1;
        @endphp
        @foreach($foods as $food)
            <tr>
                <th scope="row">{{ $loop->iteration + (($p - 1) * $foods->perPage()) }}</th>
                <td><img src="{{ $food->picture->thumbnail->absolute_path }}" width="80" height="40"/></td>
                <td>{{ $food->name_fa }}
                    @if(!$food->default_unit)
                        <i class="mdi mdi-alert text-warning" title="سروینگ پیشفرض انتخاب نشده است."></i>
                    @endif
                    @if(!count($food->units))
                        <i class="mdi mdi-alert text-danger" title="این غذا سروینگی ندارد."></i>
                    @endif
                </td>
                <td>{{ $food->name_en }}</td>
                <td>
                    <button class="mb-1 btn btn-outline-dark" type="button"
                            onclick="window.location.href= '{{ Route('admin_food_edit',['id' => $food->id]) }}'">
                        ویرایش
                    </button>

                    <button class="mb-1 btn btn-outline-danger" type="button"
                            onclick="confirmDelete({{ $food->id }})">حذف
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="mt-2">{{ $foods->render() }}</div>
