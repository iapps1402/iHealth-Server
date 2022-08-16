<?php

namespace App\Http\Controllers\Admin\Application;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApplicationManageController extends Controller
{
    private function generateItems(Request $request)
    {
        $applications = Application::orderByDesc('id');

        if ($request->has('q'))
            $applications->where(function ($q) use ($request) {
                $q->where('name_fa', 'like', '%' . $request->q . '%')
                    ->orWhere('name_en', 'like', '%' . $request->q . '%');
            });

        return $applications->paginate();
    }

    public function index(Request $request)
    {
        if ($request->isMethod('GET')) {
            $applications = $this->generateItems($request);

            $page = [
                'title' => 'مدیریت اپلیکیشن ها',
                'description' => 'مدیریت اپلیکیشن ها'
            ];

            return view('admin.application.manage', compact('applications', 'page'));
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

                    Application::findOrFail($request->id)->delete();

                    $applications = $this->generateItems($request);

                    return response()->json([
                        'success' => true,
                        'message' => 'اپلیکیشن با موفقیت حذف شد.',
                        'view' => view('admin.activity.components.manage_table', compact('applications'))->render(),
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
