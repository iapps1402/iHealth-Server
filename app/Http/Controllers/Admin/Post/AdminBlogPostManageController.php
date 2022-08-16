<?php

namespace App\Http\Controllers\Admin\Post;

use App\Helpers\HelperMedia;
use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminBlogPostManageController extends Controller
{
    private function getItems(Request $request)
    {
        $posts = BlogPost::with(['category'])->orderByDesc('id');

        if ($request->has('q'))
            $posts->where('title', 'like', '%' . $request->q . '%');

        return $posts->paginate();
    }

    public function index(Request $request)
    {
        if ($request->isMethod('GET')) {

            $posts = $this->getItems($request);

            $page = [
              'title' => 'مدیریت پست ها',
              'description' => 'مدیریت پست ها'
            ];

            return view('admin.blog.manage', compact('posts', 'page'));
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

                    $post = BlogPost::find($request->id);
                    if ($post == null)
                        return response()->json([
                            'success' => false,
                            'message' => 'مطلب یافت نشد.'
                        ]);

                    try {
                        DB::beginTransaction();
                        HelperMedia::delete($post->picture_id);
                        $post->delete();
                        DB::commit();
                    } catch (Exception $e) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'خطایی به هنگام انجام عملیات رخ داد.'
                        ]);
                    }

                    $posts = $this->getItems($request);

                    return response()->json([
                        'success' => true,
                        'message' => 'مطلب با موفقیت حذف شد.',
                        'view' => view('admin.blog.components.manage_table', compact('posts'))->render()
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
