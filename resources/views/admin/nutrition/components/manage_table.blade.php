<div class="card card-default">
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">نام مشتری</th>
            <th scope="col">برنامه نویس</th>
            <th scope="col">تاریخ انتشار</th>
            <th scope="col" width="30%">عملیات</th>
        </tr>
        </thead>
        <tbody>

        @php
        $p = Request()->has('page') ? Request()->page : 1;
        @endphp
        @foreach($programs as $program)
            <tr>
                <th scope="row">{{ $loop->iteration + (($p - 1) * $programs->perPage()) }}</th>
               <td>{{ $program->user->full_name }}</td>
               <td>{{ $program->writer->full_name }}</td>
               <td>{{ $program->created_at_shamsi }}</td>
                <td>
                    <button class="mb-1 btn btn-outline-info" type="button"
                            onclick="window.location.href='{{ route('admin_diet_program_details', ['id' => $program->id]) }}'">مشاهده
                    </button>

                    <button class="mb-1 btn btn-outline-primary" type="button"
                            onclick="window.location.href='{{ route('admin_diet_program_pdf_output', ['id' => $program->id]) }}'">دریافت PDF
                    </button>

                    <button class="mb-1 btn btn-outline-secondary" type="button"
                            onclick="window.location.href='{{ route('admin_diet_program_edit', ['id' => $program->id]) }}'">ویرایش
                    </button>

                    <button class="mb-1 btn btn-outline-danger" type="button"
                            onclick="confirmDelete({{ $program->id }})">حذف
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="mt-2">{{ $programs->render() }}</div>
