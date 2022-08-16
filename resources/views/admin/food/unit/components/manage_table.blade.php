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
        @foreach($units as $unit)
            <tr>
                <th scope="row">{{ $loop->iteration + (($p - 1) * $units->perPage()) }}</th>
                <td>{{ $unit->name_fa }}
                    @if($unit->default)
                        <span class="badge badge-warning"><i class="mdi mdi-attachment"></i> </span>
                    @endif
                </td>
                <td>{{ $unit->name_en }}</td>
                <td>
                    <button class="mb-1 btn btn-outline-dark" type="button"
                            onclick="window.location.href= '{{ Route('admin_food_unit_edit',['food_id' => $unit->food_id, 'id' => $unit->id]) }}'">
                        ویرایش
                    </button>

                    @if(!$unit->default)
                        <button class="mb-1 btn btn-outline-warning" type="button"
                                onclick="changeDefault({{ $unit->id }})"> انتخاب به عنوان پیشفرض
                        </button>
                    @endif

                    <button class="mb-1 btn btn-outline-info" type="button"
                            onclick="window.location.href= '{{ Route('admin_food_unit_material_manage',['food_id' => $unit->food_id, 'unit_id' => $unit->id]) }}'">
                        مخلفات
                    </button>

                    <button class="mb-1 btn btn-outline-danger" type="button"
                            onclick="confirmDelete({{ $unit->id }})">حذف
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="mt-2">{{ $units->render() }}</div>
