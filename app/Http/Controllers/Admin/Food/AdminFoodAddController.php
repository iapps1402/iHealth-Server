<?php

namespace App\Http\Controllers\Admin\Food;

use App\Helpers\HelperMedia;
use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\FoodCategory;
use App\Models\FoodCooking;
use App\Models\FoodCategoryRelation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AdminFoodAddController extends Controller
{
    public function add(Request $request)
    {
        if ($request->isMethod('GET')) {
            $page = [
                'title' => 'افزودن غذا',
                'description' => 'افزودن غذا'
            ];

            $categories = FoodCategory::all();

            return view('admin.food.add', [
                'page' => $page,
                'categories' => $categories
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name_fa' => 'required|string|max:191',
            'name_en' => 'required|string|max:191',
            'categories' => 'required|json',
            'cooking_time' => 'nullable|numeric',
            'cooking_calorie' => 'nullable|numeric',
            'cooking_amount_fa' => 'nullable|string',
            'cooking_amount_en' => 'nullable|string',
            'description_fa' => 'nullable|string',
            'description_en' => 'nullable|string',
            'barcode' => 'nullable|string'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message<br>')
            ]);

        $categoriesJson = json_decode($request->categories, TRUE);

        try {
            DB::beginTransaction();
            $cooking = FoodCooking::create([
                'amount_en' => $request->cooking_amount_en,
                'amount_fa' => $request->cooking_amount_fa,
                'time' => $request->cooking_time,
                'calorie' => $request->cooking_calorie,
            ]);


            $food = Food::create([
                'name_en' => $request->name_en,
                'picture_id' => HelperMedia::uploadPicture($request->file('picture'), 'food/pictures/', 1280, 850),
                'name_fa' => $request->name_fa,
                'cooking_id' => $cooking->id,
                'description_fa' => $request->description_fa,
                'description_en' => $request->description_en,
                'barcode' => $request->barcode
            ]);

            foreach ($categoriesJson as $category)
                FoodCategoryRelation::create([
                    'food_id' => $food->id,
                    'category_id' => $category
                ]);

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'خطا رخ داد.'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'غذا با موفقیت افزوده شد.',
            'redirect_url' => Route('admin_food_edit', ['id' => $food->id])
        ]);
    }

    public function edit($id, Request $request)
    {
        $food = Food::with(['picture.thumbnail', 'categories', 'cooking'])->findOrFail($id);

        if ($request->isMethod('GET')) {
            $categories = FoodCategory::all();

            $page = [
                'title' => 'ویرایش ' . $food->name_fa,
                'description' => 'ویرایش ' . $food->name_fa
            ];

            return view('admin.food.add', compact('categories', 'food', 'page'));
        }

        $validator = Validator::make($request->all(), [
            'name_fa' => 'required|string|max:191',
            'name_en' => 'required|string|max:191',
            'categories' => 'required|json',
            'cooking_time' => 'nullable|numeric',
            'cooking_calorie' => 'nullable|numeric',
            'cooking_amount_fa' => 'nullable|string',
            'cooking_amount_en' => 'nullable|string',
            'description_fa' => 'nullable|string',
            'description_en' => 'nullable|string',
            'barcode' => 'nullable|string'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message<br>')
            ]);

        $categoriesJson = json_decode($request->categories, TRUE);

        try {
            DB::beginTransaction();
            $food->update([
                'name_en' => $request->name_en,
                'name_fa' => $request->name_fa,
                'picture_id' => empty($request->picture) ? $food->picture_id : HelperMedia::uploadPicture($request->file('picture'), 'food/pictures/', 1280, 850, false, $food->picture),
                'description_fa' => $request->description_fa,
                'description_en' => $request->description_en,
                'barcode' => $request->barcode
            ]);

            $food->cooking->update([
                'time' => $request->cooking_time,
                'calorie' => $request->cooking_calorie,
                'amount_en' => $request->cooking_amount_en,
                'amount_fa' => $request->cooking_amount_fa,
            ]);

            FoodCategoryRelation::where('food_id', $food->id)
                ->whereNotIn('category_id', $categoriesJson)
                ->delete();

            foreach ($categoriesJson as $category) {
                if (!FoodCategoryRelation::where('food_id', $food->id)
                    ->where('category_id', $category)->exists())
                    FoodCategoryRelation::create([
                        'food_id' => $food->id,
                        'category_id' => $category
                    ]);
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'خطا رخ داد.'
            ]);
        }


        $food = Food::with(['picture.thumbnail'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'غذا با موفقیت ویرایش شد.',
            'food' => $food
        ]);
    }
}
