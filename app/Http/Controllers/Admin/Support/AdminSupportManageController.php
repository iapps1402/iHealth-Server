<?php

namespace App\Http\Controllers\Admin\Support;

use App\Helpers\HelperMedia;
use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Support;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminSupportManageController extends Controller
{
    private function getItems(Request $request)
    {
        $supports = Support::with(['photo.thumbnail'])->orderByDesc('created_at');

        if ($request->has('q'))
            $supports->where('full_name', 'like', '%' . $request->q . '%');

        return $supports->paginate();
    }

    public function index(Request $request)
    {
        if ($request->isMethod('GET')) {

            $supports = $this->getItems($request);

            $page = [
                'title' => 'مدیریت پشتیبان ها',
                'description' => 'مدیریت پشتیبان ها'
            ];

            return view('admin.support.manage', compact('supports', 'page'));
        } else {
            switch ($request->action) {
                case 'delete':
                    if (Validator::make($request->all(), [
                        'id' => 'required|numeric|not_in:0'
                    ])->fails())
                        return response()->json([
                            'success' => false,
                            'message' => 'فیلد id ارسال نشده است.'
                        ]);

                    $support = Support::findOrFail($request->id);

                    try {
                        DB::beginTransaction();
                        HelperMedia::delete($support->photo_id);
                        $support->delete();
                        DB::commit();
                    } catch (Exception $e) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'خطایی به هنگام انجام عملیات رخ داد.'
                        ]);
                    }

                    $supports = $this->getItems($request);

                    return response()->json([
                        'success' => true,
                        'message' => 'پشتیبان با موفقیت حذف شد.',
                        'view' => view('admin.support.components.manage_table', compact('supports'))->render()
                    ]);

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'فیلد action ارسال نشده است.'
                    ]);
            }
        }
    }
}
