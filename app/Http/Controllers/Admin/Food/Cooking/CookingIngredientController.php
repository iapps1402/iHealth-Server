<?php

namespace App\Http\Controllers\Admin\Food\Cooking;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\FoodCookingIngredient;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CookingIngredientController extends Controller
{
    private function generateItems($foodId, Request $request)
    {
        $ingredients = FoodCookingIngredient::whereHas('cooking.food', function ($q) use($foodId) {
            $q->where('id', $foodId);
        })->orderByDesc('id');

        if ($request->has('q'))
            $ingredients->where(function ($q) use($request) {
                $q->where('name_fa', 'like', '%' . $request->q . '%')
                ->orWhere('name_en', 'like', '%' . $request->q . '%');
            });


        return $ingredients->paginate();
    }

    public function index($foodId, Request $request)
    {
        $food = Food::findOrFail($foodId);

        if ($request->isMethod('GET')) {

            $ingredients = $this->generateItems($foodId, $request);

            $page = [
                'title' => 'مدیریت مواد لازم',
                'description' => 'مدیریت مواد لازم'
            ];

            return view('admin.food.cooking.ingredient.manage', [
                'page' => $page,
                'ingredients' => $ingredients
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

                FoodCookingIngredient::findOrFail($request->id)->delete();
                $ingredients = $this->generateItems($foodId, $request);

                return response()->json([
                    'success' => true,
                    'view' => view('admin.food.cooking.ingredient.components.manage_table', compact('ingredients'))->render(),
                    'message' => 'عنصر با موفقیت حذف شد.'
                ]);
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'فیلد action ارسال نشده است.'
                ]);

            case 'add':
                $validator = Validator::make($request->all(), [
                    'name_fa' => 'required|string|max:191',
                    'value_fa' => 'required|string|max:191',
                    'name_en' => 'required|string|max:191',
                    'value_en' => 'required|string|max:191'
                ]);

                if ($validator->fails())
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()->all(':message')
                    ]);

                if (FoodCookingIngredient::where(function ($q) use($request) {
                    $q->where('name_en', $request->name_en)
                        ->orWhere('name_fa', $request->name_fa);
                })
                    ->whereHas('cooking.food', function ($q) use($foodId) {
                        $q->where('id', $foodId);
                    })
                    ->exists())
                    return response()->json([
                        'success' => false,
                        'message' => 'این عنصر قبلا ثبت شده است.'
                    ]);

                $ingredient = FoodCookingIngredient::create([
                    'name_fa' => $request->name_fa,
                    'value_fa' => $request->value_fa,
                    'name_en' => $request->name_en,
                    'value_en' => $request->value_en,
                    'cooking_id' => $food->cooking_id
                ]);

                $ingredients = $this->generateItems($foodId, $request);

                return response()->json([
                    'success' => true,
                    'category' => $ingredient,
                    'view' => view('admin.food.cooking.ingredient.components.manage_table', compact('ingredients'))->render(),
                    'message' => 'عنصر با موفقیت افزوده شد.'
                ]);

            case 'edit':
                $validator = Validator::make($request->all(), [
                    'id' => 'required|numeric|not_in:0',
                    'name_fa' => 'required|string|max:191',
                    'value_fa' => 'required|string|max:191',
                    'name_en' => 'required|string|max:191',
                    'value_en' => 'required|string|max:191'
                ]);

                if ($validator->fails())
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()->all(':message')
                    ]);

                $ingredient = FoodCookingIngredient::findOrFail($request->id);

                if (FoodCookingIngredient::where('id', '<>', $ingredient->id)
                    ->whereHas('cooking.food', function ($q) use($foodId) {
                        $q->where('id', $foodId);
                    })
                    ->where(function ($q) use($request) {
                       $q->where('name_en', $request->name_en)
                       ->orWhere('name_fa', $request->name_fa);
                    })
                    ->exists())
                    return response()->json([
                        'success' => false,
                        'message' => 'عنصری با این نام قبلا ثبت شده است.'
                    ]);

                $ingredient->update([
                    'name_fa' => $request->name_fa,
                    'value_fa' => $request->value_fa,
                    'name_en' => $request->name_en,
                    'value_en' => $request->value_en
                ]);

                $ingredients = $this->generateItems($foodId, $request);

                return response()->json([
                    'success' => true,
                    'view' => view('admin.food.cooking.ingredient.components.manage_table', compact('ingredients'))->render(),
                    'message' => 'عنصر با موفقیت ویرایش شد.'
                ]);
        }
    }
}
