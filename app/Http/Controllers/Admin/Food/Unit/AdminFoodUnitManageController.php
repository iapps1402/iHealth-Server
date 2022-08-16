<?php

namespace App\Http\Controllers\Admin\Food\Unit;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\FoodUnit;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminFoodUnitManageController extends Controller
{
    private function getItems($foodId, Request $request)
    {
        $units = FoodUnit::orderByDesc('id')
            ->where('food_id', $foodId);

        if ($request->has('q'))
            $units->where(function ($q) use ($request) {
                $q->where('name_fa', 'like', '%' . $request->q . '%')
                    ->orWhere('name_en', 'like', '%' . $request->q . '%');
            });

        return $units->paginate();
    }

    public function index($foodId, Request $request)
    {
        $food = Food::findOrFail($foodId);

        if ($request->isMethod('GET')) {
            $units = $this->getItems($foodId, $request);

            $page = [
                'title' => 'مدیریت واحدهای ' . $food->name_fa,
                'description' => 'مدیریت واحدهای ' . $food->name_fa,
            ];

            return view('admin.food.unit.manage', compact('units', 'food', 'page'));
        }

        switch ($request->action) {
            case 'delete':
                $validator = Validator::make($request->all(), [
                    'id' => 'required|numeric|not_in:0'
                ]);

                if ($validator->fails())
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()->all(':message')
                    ]);

                $unit = FoodUnit::where('id', $request->id)
                    ->where('food_id', $foodId)
                    ->first();

                if ($unit == null)
                    return response()->json([
                        'success' => false,
                        'message' => 'واحد یافت نشد.'
                    ]);

                if ($unit->default)
                    return response()->json([
                        'success' => false,
                        'message' => 'این واحد پیشفرض است. لطفا ابتدا واحد پیشفرض دیگری انتخاب کنید و سپس اقدام به حذف این واحد کنید.'
                    ]);

                try {

                    if ($unit->default) {
                        $secondaryUnit = FoodUnit::where('food_id', $unit->food_id)->first();
                        if ($secondaryUnit != null)
                            $secondaryUnit->update([
                                'default' => 1
                            ]);
                    }

                    $unit->delete();

                } catch (Exception $exception) {
                    return response()->json([
                        'success' => false,
                        'message' => 'این واحد قابل حذف نیست.'
                    ]);
                }

                $units = $this->getItems($foodId, $request);

                return response()->json([
                    'success' => true,
                    'message' => 'واحد با موفقیت حذف شد.',
                    'view' => view('admin.food.unit.components.manage_table', compact('units'))->render()
                ]);

            case 'default':
                $validator = Validator::make($request->all(), [
                    'id' => 'required|numeric|not_in:0'
                ]);

                if ($validator->fails())
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()->all(':message')
                    ]);

                $unit = FoodUnit::where('id', $request->id)
                    ->where('food_id', $foodId)
                    ->first();

                if ($unit == null)
                    return response()->json([
                        'success' => false,
                        'message' => 'واحد یافت نشد.'
                    ]);

                try {
                    DB::beginTransaction();
                    FoodUnit::where('food_id', $unit->food_id)->update([
                        'default' => 0
                    ]);

                    $unit->update([
                        'default' => 1
                    ]);
                    DB::commit();
                } catch (Exception $exception) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'خطا رخ داد.'
                    ]);
                }

                $units = $this->getItems($foodId, $request);

                return response()->json([
                    'success' => true,
                    'message' => 'واحد پیشفرض با موفقیت انتخاب شد.',
                    'view' => view('admin.food.unit.components.manage_table', compact('units'))->render()
                ]);
        }
    }
}
