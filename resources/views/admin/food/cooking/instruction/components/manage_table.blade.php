<div class="card card-default">
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">مرحله</th>
            <th scope="col" width="30%">عملیات</th>
        </tr>
        </thead>
        <tbody>

        @php
            $p = Request()->has('page') ? Request()->page : 1;
        @endphp

        @foreach($instructions as $instruction)
            <tr>
                <th scope="row">{{ $loop->iteration + (($p - 1) * $instructions->perPage()) }}</th>
                <td>{{ $instruction->text_en }}<div class="text-muted">{{ $instruction->text_fa  }}</div> </td>
                <td>
                    <button class="mb-1 btn btn-outline-dark" type="button" onclick="editItem({{ $instruction }})">ویرایش</button>
                    <button class="mb-1 btn btn-outline-danger" type="button" onclick="confirmDelete({{ $instruction->id }})">حذف</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="mt-2">{{ $instructions->render() }}</div>
