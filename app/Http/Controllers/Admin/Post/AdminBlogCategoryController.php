<?php

namespace App\Http\Controllers\Admin\Post;

use App\Http\Controllers\Controller;
use App\Models\BlogPostCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminBlogCategoryController extends Controller
{
    private function getItems(Request $request)
    {
        $categories = BlogPostCategory::orderByDesc('id');

        if ($request->has('q'))
            $categories->where(function ($q) use ($request) {
                $q->where('name_fa', 'like', '%' . $request->q . '%')
                    ->orWhere('name_en', 'like', '%' . $request->q . '%');
            });

        return $categories->paginate();
    }

    public function index($parentId, Request $request)
    {
        if ($request->isMethod('GET')) {
            $categories = $this->getItems($request);

            $page = [
                'title' => 'مدیریت دسته بندی ها',
                'description' => 'مدیریت دسته بندی ها',
            ];

            return view('admin.blog.categories', compact('categories', 'parentId', 'page'));
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
                $category = BlogPostCategory::find($request->id);
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

                $categories = $this->getItems($request);

                return response()->json([
                    'success' => true,
                    'view' => view('admin.blog.components.categories_table', compact('categories'))->render(),
                    'message' => 'دسته بندی با موفقیت حذف شد.'
                ]);

            case 'add':
                $validator = Validator::make($request->all(), [
                    'name_fa' => 'required|string|max:191',
                    'name_en' => 'required|string|max:191',
                ]);

                if ($validator->fails())
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()->all(':message')
                    ]);

                if (BlogPostCategory::where(function ($q) use ($request) {
                    $q->where('name_fa', $request->name_fa)
                        ->orWhere('name_en', $request->name_en);
                })->exists())
                    return response()->json([
                        'success' => false,
                        'message' => 'این دسته بندی قبلا ثبت شده است.'
                    ]);

                $category = BlogPostCategory::create([
                    'name_fa' => $request->name_fa,
                    'name_en' => $request->name_en
                ]);

                $categories = $this->getItems($request);

                return response()->json([
                    'success' => true,
                    'category' => $category,
                    'view' => view('admin.blog.components.categories_table', compact('categories'))->render(),
                    'message' => 'دسته بندی با موفقیت افزوده شد.'
                ]);

            case 'edit':
                $validator = Validator::make($request->all(), [
                    'id' => 'required|numeric|not_in:0',
                    'name_fa' => 'required|string|max:191',
                    'name_en' => 'required|string|max:191',
                ]);

                if ($validator->fails())
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()->all(':message')
                    ]);

                $category = BlogPostCategory::find($request->id);
                if ($category == null)
                    return response()->json([
                        'success' => false,
                        'message' => 'دسته بندی یافت نشد.'
                    ]);

                if (BlogPostCategory::where('id', '<>', $category->id)
                    ->where(function ($q) use ($category, $request) {
                        $q->where('name_fa', $request->name_fa)
                            ->orWhere('name_en', $request->name_en);
                    })->exists())
                    return response()->json([
                        'success' => false,
                        'message' => 'دسته بندی با این نام قبلا ثبت شده است.'
                    ]);

                $category->update([
                    'name_fa' => $request->name_fa,
                    'name_en' => $request->name_en,
                ]);

                $categories = $this->getItems($request);

                return response()->json([
                    'success' => true,
                    'view' => view('admin.blog.components.categories_table', compact('categories'))->render(),
                    'message' => 'دسته بندی با موفقیت ویرایش شد.'
                ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'فیلد action به درستی ارسال نشده است.'
        ]);
    }
}
