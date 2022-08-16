<?php

namespace App\Http\Controllers\Admin\Food\Category;

use App\Helpers\HelperMedia;
use App\Http\Controllers\Controller;
use App\Models\FoodCategory;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminFoodCategoryAddController extends Controller
{
    public function add(Request $request)
    {
        if ($request->isMethod('GET')) {

            $page = [
                'title' => 'افزودن دسته بندی',
                'description' => 'افزودن دسته بندی',
            ];

            return view('admin.food.category.add', compact('page'));
        }
        $validator = Validator::make($request->all(), [
            'picture' => 'required|mimes:jpeg,jpg,png|max:20000',
            'name_en' => 'required|string|max:191',
            'name_fa' => 'required|string|max:191',
            'in_app' => 'required|boolean'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message<br>')
            ]);

        $category = FoodCategory::create([
            'picture_id' => HelperMedia::uploadPicture($request->picture, 'food/category/', 800, 400),
            'name_en' => $request->name_en,
            'name_fa' => $request->name_fa,
            'in_app' => $request->in_app,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'دسته بندی با موفقیت افزوده شد.',
            'redirect_url' => Route('admin_food_category_edit', ['id' => $category->id])
        ]);
    }

    public function edit($id, Request $request)
    {
        $category = FoodCategory::with(['picture.thumbnail'])->findOrFail($id);

        if ($request->isMethod('GET')) {
            $page = [
                'title' => 'ویرایش دسته بندی' . $category->name_en,
                'description' => 'ویرایش دسته بندی' . $category->name_en
            ];

            return view('admin.food.category.add', compact('category', 'page'));
        }

        $validator = Validator::make($request->all(), [
            'picture' => 'nullable|mimes:jpeg,jpg,png|max:20000',
            'name_en' => 'required|string|max:191',
            'name_fa' => 'required|string|max:191',
            'in_app' => 'required|boolean'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message<br>')
            ]);

        $category->update([
            'picture_id' => $request->picture == null ? $category->picture_id : HelperMedia::uploadPicture($request->picture, 'food/category/', 800, 400, false, $category->picture),
            'name_en' => $request->name_en,
            'name_fa' => $request->name_fa,
            'in_app' => $request->in_app,
        ]);

        $category = FoodCategory::with(['picture.thumbnail'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'دسته بندی با موفقیت ویرایش شد.',
            'category' => $category
        ]);
    }
}
