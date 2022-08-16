<?php

namespace App\Http\Controllers\Admin\Food\Unit;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\FoodUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminFoodUnitAddController extends Controller
{
    public function add($foodId, Request $request)
    {
        $food = Food::findOrFail($foodId);

        if ($request->isMethod('GET')) {
            $page = [
                'title' => 'افزودن واحد به ' . $food->name_fa,
                'description' => 'افزودن واحد به ' . $food->name_fa
            ];

            return view('admin.food.unit.add', compact('food', 'page'));
        }

        $validator = Validator::make($request->all(), [
            'name_fa' => 'required|string|max:191',
            'name_en' => 'required|string|max:191',
            'carbs' => 'required|numeric',
            'fat' => 'required|numeric',
            'protein' => 'required|numeric',
            'fiber' => 'required|numeric',
            'number' => 'required|numeric|not_in:0',
            'calorie' => 'nullable|numeric',
            'icon' => 'required|string'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message<br>')
            ]);

        $unit = FoodUnit::create([
            'food_id' => $foodId,
            'name_en' => $request->name_en,
            'name_fa' => $request->name_fa,
            'carbs' => $request->carbs,
            'fat' => $request->fat,
            'protein' => $request->protein,
            'number' => $request->number,
            'fiber' => $request->fiber,
            'calorie' => $request->calorie,
            'icon' => $request->icon,
            'default' => !FoodUnit::where('food_id', $foodId)->exists()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'واحد با موفقیت افزوده شد.',
            'redirect_url' => route('admin_food_unit_edit', [
                'id' => $unit->id,
                'food_id' => $foodId
            ])
        ]);
    }

    public function edit($foodId, $id, Request $request)
    {
        $unit = FoodUnit::findOrFail($id);
        $food = Food::findOrFail($foodId);

        if ($food->id != $unit->food_id)
            return abort(404);

        if ($request->isMethod('GET')) {
            $page = [
                'title' => 'ویرایش واحد',
                'description' => 'ویرایش واحد',
            ];

            return view('admin.food.unit.add', compact('unit', 'food', 'page'));
        }

        $validator = Validator::make($request->all(), [
            'name_fa' => 'required|string|max:191',
            'name_en' => 'required|string|max:191',
            'carbs' => 'required|numeric',
            'fat' => 'required|numeric',
            'protein' => 'required|numeric',
            'fiber' => 'required|numeric',
            'number' => 'required|numeric|not_in:0',
            'calorie' => 'nullable|numeric',
            'icon' => 'required|string'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message')
            ]);

        $unit->update([
            'name_en' => $request->name_en,
            'name_fa' => $request->name_fa,
            'carbs' => $request->carbs,
            'fat' => $request->fat,
            'protein' => $request->protein,
            'number' => $request->number,
            'fiber' => $request->fiber,
            'calorie' => $request->calorie,
            'icon' => $request->icon,
            'default' => !FoodUnit::where('food_id', $foodId)->exists()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'واحد با موفقیت ویرایش شد.'
        ]);
    }
}
