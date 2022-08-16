<?php

namespace App\Http\Controllers\Admin\Upload;

use App\Helpers\HelperMedia;
use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminUploadManageController extends Controller
{
    private function getItems(Request $request)
    {
        $medias = Media::where('uploaded', 1)->orderByDesc('created_at');

        if ($request->has('q'))
            $medias->where('filename', 'like', '%' . $request->q . '%');

        return $medias->paginate();
    }

    public function index(Request $request)
    {
        if ($request->isMethod('GET')) {
            $medias = $this->getItems($request);

            $page = [
                'title' => 'مدیریت فایل های آپلود شده',
                'description' => 'مدیریت فایل های آپلود شده'
            ];

            return view('admin.upload.manage', compact('medias', 'page'));
        }

        switch ($request->action) {
            case 'upload':
                $validator = Validator::make($request->all(), [
                    'file' => 'required|file|mimes:jpeg,jpg,png|max:20000'
                ]);

                if ($validator->fails())
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()->all(':message<br>')
                    ]);

                Media::find(HelperMedia::uploadFile($request->file('file'), 'uploads/'));

                $medias = $this->getItems($request);

                return response()->json([
                    'success' => true,
                    'message' => 'فایل با موفقیت آپلود شد.',
                    'view' => view('admin.upload.component.manage_table', compact('medias'))->render()
                ]);

            case 'delete':
                if (Validator::make($request->all(), [
                    'id' => 'required|numeric|not_in:0'
                ])->fails())
                    return response()->json([
                        'success' => false,
                        'message' => 'فیلد id ارسال نشده است.'
                    ]);

                Media::findOrFail($request->id);

                HelperMedia::delete($request->id);

                $medias = $this->getItems($request);
                return response()->json([
                    'success' => true,
                    'message' => 'فایل با موفقیت حذف شد.',
                    'view' => view('admin.upload.component.manage_table', compact('medias'))->render()
                ]);
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'فیلد action ارسال نشده است.'
                ]);
        }
    }
}
