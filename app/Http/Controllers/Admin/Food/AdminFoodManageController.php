<?php

namespace App\Http\Controllers\Admin\Food;

use App\Helpers\HelperMedia;
use App\Http\Controllers\Controller;
use App\Models\Food;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminFoodManageController extends Controller
{
    private function generateItems(Request $request)
    {
        $foods = Food::orderByDesc('id');

        if ($request->has('q'))
            $foods->where(function ($q) use ($request) {
                $q->where('name_fa', 'like', '%' . $request->q . '%')
                    ->orWhere('name_en', 'like', '%' . $request->q . '%');
            });

        return $foods->paginate();
    }

    public function index(Request $request)
    {
        if ($request->isMethod('GET')) {

            $foods = $this->generateItems($request);

            $page = [
                'title' => 'مدیریت غذاها',
                'description' => 'مدیریت غذاها'
            ];

            return view('admin.food.manage', [
                'page' => $page,
                'foods' => $foods
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

                $food = Food::find($request->id);
                if ($food == null)
                    return response()->json([
                        'success' => false,
                        'message' => 'غذا یافت نشد.'
                    ]);

                try {
                    DB::beginTransaction();
                    HelperMedia::delete($food->picture_id);
                    $food->delete();
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'این غذا قابل حذف نیست.'
                    ]);
                }

                $foods = $this->generateItems($request);

                return response()->json([
                    'success' => true,
                    'view' => view('admin.food.components.manage_table', compact('foods'))->render(),
                    'message' => 'غذا با موفقیت حذف شد.'
                ]);
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'فیلد action ارسال نشده است.'
                ]);
        }
    }
}
