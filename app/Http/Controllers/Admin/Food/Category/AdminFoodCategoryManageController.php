<?php

namespace App\Http\Controllers\Admin\Food\Category;

use App\Http\Controllers\Controller;
use App\Models\FoodCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminFoodCategoryManageController extends Controller
{
    private function generateItems(Request $request)
    {
        $categories = FoodCategory::with(['picture.thumbnail'])->orderByDesc('id');

        if ($request->has('q'))
            $categories->where(function ($q) use ($request) {
                $q->where('name_fa', 'like', '%' . $request->q . '%')
                    ->orWhere('name_en', 'like', '%' . $request->q . '%');
            });

        return $categories->paginate();
    }

    public function index(Request $request)
    {
        if ($request->isMethod('GET')) {

            $page = [
                'title' => 'مدیریت دسته بندی ها',
                'description' => 'مدیریت دسته بندی ها'
            ];

            $categories = $this->generateItems($request);

            return view('admin.food.category.manage', compact('categories', 'page'));
        }
        switch ($request->action) {
            case 'delete':
                $validator = Validator::make($request->all(), [
                    'id' => 'required|numeric|not_in:0',
                ]);

                if ($validator->fails())
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()->all(':message')
                    ]);
                $category = FoodCategory::find($request->id);
                if ($category == null)
                    return response()->json([
                        'success' => false,
                        'message' => 'دسته بندی یافت نشد.'
                    ]);
                try {
                    $category->delete();
                } catch (Exception $exception) {
                    return response()->json([
                        'success' => false,
                        'message' => 'این دسته بندی قابل حذف نیست.'
                    ]);
                }

                $categories = $this->generateItems($request);

                return response()->json([
                    'success' => true,
                    'message' => 'دسته بندی با موفقیت حذف شد.',
                    'view' => view('admin.food.category.components.manage_table', compact('categories'))->render()
                ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'فیلد action به درستی ارسال نشده است.'
        ]);
    }
}
