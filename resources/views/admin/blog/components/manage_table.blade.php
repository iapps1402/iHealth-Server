<div class="card card-default">
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">عنوان</th>
            <th scope="col">شناسه</th>
            <th scope="col">وضعیت</th>
            <th scope="col">دسته</th>
            <th scope="col" width="30%">عملیات</th>
        </tr>
        </thead>
        <tbody>

        @php
            $p = Request()->has('page') ? Request()->page : 1;
        @endphp
        @foreach($posts as $post)
            <tr>
                <th scope="row">{{ $loop->iteration + (($p - 1) * $posts->perPage()) }}</th>
                <td>{{ $post->title }}</td>
                <td>{{ $post->id }}</td>
                <td>
                    @if($post->status =='draft')
                        <div class="badge badge-warning">پیش نویس</div>
                    @else
                        <div class="badge badge-success">منتشر شده</div>
                    @endif
                </td>
                <td>{{ $post->category->name_fa }}</td>
                <td>
                    <button class="mb-1 btn btn-outline-dark" type="button"
                            onclick="window.location.href= '{{ Route('admin_blog_post_edit',['id' => $post->id]) }}'">
                        ویرایش
                    </button>
                    <button class="mb-1 btn btn-outline-danger" type="button"
                            onclick="confirmDelete({{ $post->id }})">حذف
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="mt-2">{{ $posts->render() }}</div>
