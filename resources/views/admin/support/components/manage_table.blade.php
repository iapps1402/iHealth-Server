<div class="card card-default">
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">نام</th>
            <th scope="col">تلگرام</th>
            <th scope="col">واتساپ</th>
            <th scope="col" width="30%">عملیات</th>
        </tr>
        </thead>
        <tbody>

        @php
            $p = Request()->has('page') ? Request()->page : 1;
        @endphp
        @foreach($supports as $support)
            <tr>
                <th scope="row">{{ $loop->iteration + (($p - 1) * $supports->perPage()) }}</th>
                <td>{{ $support->full_name }}</td>
                <td>{{ $support->telegram_id }}</td>
                <td>{{ $support->whatsapp_number }}</td>
                <td>
                    <button class="mb-1 btn btn-outline-dark" type="button"
                            onclick="window.location.href= '{{ Route('admin_support_edit',['id' => $support->id]) }}'">
                        ویرایش
                    </button>
                    <button class="mb-1 btn btn-outline-danger" type="button"
                            onclick="confirmDelete({{ $support->id }})">حذف
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="mt-2">{{ $supports->render() }}</div>
