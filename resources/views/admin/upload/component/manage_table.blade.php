@if(count($medias) > 0)
    <div class="row">
        <div class="col-lg-8 col-md-6">

        </div>
        <div class="col-lg-4 col-md-6">
            <form class="search-bar p-3 float-le" method="get" action="">
                <div class="position-relative">
                    <input name="q" value="{{ Request()->q }}" type="text" class="form-control" placeholder="جستجو...">
                </div>
            </form>
        </div>
    </div>

    <div class="card card-default">
        <div class="card-header">مدیریت تصاویر آپلود شده</div>
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">نام فایل</th>
                <th scope="col">آدرس url</th>
                <th scope="col">تصویر</th>
                <th scope="col" width="15%">عملیات</th>
            </tr>
            </thead>
            <tbody>

            @php
                $p = Request()->has('page') ? Request()->page : 1;
            @endphp
            @foreach($medias as $media)
                <tr>
                    <th scope="row">{{ $loop->iteration + (($p - 1) * $medias->perPage()) }}</th>
                    <td>{{ $media->filename }}</td>
                    <td>
                        <a onclick="addToClipboard('{{ $media->absolute_path }}')"><i
                                class="icon icon-paper-clip"></i> کپی</a></td>
                    <td>@if($media->thumbnail != null)<img src="{{ $media->thumbnail->absolute_path }}" width="50"
                                                           height="50">@endif</td>
                    <td>
                        <button class="mb-1 btn btn-outline-danger" type="button"
                                onclick="confirmDelete({{ $media->id }})">حذف
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif
