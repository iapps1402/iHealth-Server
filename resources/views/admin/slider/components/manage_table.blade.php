<div class="card card-default">
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">تصویر</th>
            <th scope="col">تاریخ ایجاد</th>
            <th scope="col" width="15%">عملیات</th>
        </tr>
        </thead>
        <tbody>

        @php
            $p = Request()->has('page') ? Request()->page : 1;
        @endphp
        @foreach($sliders as $slider)
            <tr>
                <th scope="row">{{ $loop->iteration + (($p - 1) * $sliders->perPage()) }}</th>
                <td><img src="{{ URL::to($slider->picture->thumbnail->absolute_path) }}" width="80" height="40" /> </td>
                <td>{{ \Morilog\Jalali\Jalalian::fromDateTime($slider->created_at)->format('%A, %d %B %Y ساعت H:i') }}</td>
                <td>
                    <button class="mb-1 btn btn-outline-dark" type="button" onclick="window.location.href= '{{ Route('admin_slider_edit',['id' => $slider->id]) }}'">ویرایش</button>
                    <button class="mb-1 btn btn-outline-danger" type="button" onclick="confirmDelete({{ $slider->id }})">حذف</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="mt-2">{{ $sliders->render() }}</div>
