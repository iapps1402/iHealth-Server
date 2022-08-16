<div class="card card-default">
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">غذا</th>
            <th scope="col">واحد منتخب</th>
            <th scope="col" width="30%">عملیات</th>
        </tr>
        </thead>
        <tbody>

        @php
            $p = Request()->has('page') ? Request()->page : 1;
        @endphp
        @foreach($suggestions as $suggestion)
            <tr>
                <th scope="row">{{ $loop->iteration + (($p - 1) * $suggestions->perPage()) }}</th>
                <td>{{ $suggestion->food->name_fa }}</td>
                <td>به ازای هر {{ $suggestion->unit->name_fa }}</td>
                <td>
                    <button class="mb-1 btn btn-outline-danger" type="button"
                            onclick="confirmDelete({{ $suggestion->id }})">حذف
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="mt-2">{{ $suggestions->render() }}</div>
