<?php

namespace App\Http\Controllers\Admin\Diet;

use App\Http\Controllers\Controller;
use App\Models\DietProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DietProgramManageController extends Controller
{
    private function generateItems(Request $request)
    {
        $programs = DietProgram::with(['days.meals.items.unit.food.categories', 'user', 'writer'])
            ->orderByDesc('id');

        if ($request->has('q'))
            $programs->whereHas('user', function ($q) use ($request) {
                $q->whereRaw("CONCAT(`first_name`, ' ', `last_name`) like '%$request->q%'")
                    ->orWhere(function ($q) use($request) {
                        $q->whereNotNull('phone_number')
                            ->where('phone_number', 'like', '%' . $request->q . '%');
                    })
                    ->orWhere(function ($q) use($request) {
                        $q->whereNotNull('email')
                            ->where('email', 'like', '%' . $request->q . '%');
                    });
            });

        return $programs->paginate();
    }

    public function index(Request $request)
    {
        if ($request->isMethod('GET')) {

            $programs = $this->generateItems($request);

            $page = [
                'title' => 'مدیریت برنامه های تغذیه',
                'description' => 'مدیریت برنامه های تغذیه',
            ];

            return view('admin.nutrition.manage', [
                'page' => $page,
                'programs' => $programs
            ]);
        }

        switch ($request->action) {
            case 'delete':
                if (Validator::make($request->all(), [
                    'id' => 'required|numeric|not_in:0'
                ])->fails())
                    return response()->json([
                        'success' => false,
                        'message' => 'فیلد id ارسال نشده است.'
                    ]);

                DietProgram::findOrFail($request->id)->delete();

                $programs = $this->generateItems($request);

                return response()->json([
                    'success' => true,
                    'view' => view('admin.nutrition.components.manage_table', compact('programs'))->render(),
                    'message' => 'برنامه با موفقیت حذف شد.'
                ]);
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'فیلد action ارسال نشده است.'
                ]);
        }
    }
}
