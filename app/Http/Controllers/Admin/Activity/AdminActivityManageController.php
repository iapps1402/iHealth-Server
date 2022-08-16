<?php

namespace App\Http\Controllers\Admin\Activity;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminActivityManageController extends Controller
{
    private function generateItems(Request $request)
    {
        $activities = Activity::orderByDesc('id');

        if ($request->has('q'))
            $activities->where(function ($q) use ($request) {
                $q->where('name_fa', 'like', '%' . $request->q . '%')
                    ->orWhere('name_en', 'like', '%' . $request->q . '%');
            });

        return $activities->paginate();
    }

    public function index(Request $request)
    {
        if ($request->isMethod('GET')) {
            $activities = Activity::orderByDesc('id')->paginate();

            $page = [
                'title' => 'مدیریت فعالیت ها',
                'description' => 'مدیریت فعالیت ها'
            ];

            return view('admin.activity.manage', compact('activities', 'page'));
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

                    Activity::findOrFail($request->id)->delete();

                    $activities = $this->generateItems($request);

                    return response()->json([
                        'success' => true,
                        'message' => 'فعالیت با موفقیت حذف شد.',
                        'view' => view('admin.activity.components.manage_table', compact('activities'))->render(),
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
