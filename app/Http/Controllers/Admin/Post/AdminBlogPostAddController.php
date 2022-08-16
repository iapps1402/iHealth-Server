<?php

namespace App\Http\Controllers\Admin\Post;

use App\Helpers\HelperMedia;
use App\Http\Controllers\Controller;
use App\Models\BlogPostCategory;
use App\Models\BlogPost;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminBlogPostAddController extends Controller
{
    public function add(Request $request)
    {
        if ($request->isMethod('GET')) {
            $categories = BlogPostCategory::all();

            $page = [
                'title' => 'افزودن پست',
                'description' => 'افزودن پست',
            ];

            return view('admin.blog.add', compact('categories', 'page'));
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:191',
            'summary' => 'required|string|max:1000',
            'category_id' => 'required|numeric|not_in:0',
            'text' => 'required|string',
            'picture' => 'required|mimes:jpeg,jpg,png|max:20000',
            'language' => 'required|in:en,fa',
            'status' => 'required|in:published,draft'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message<br>')
            ]);

        if (!BlogPostCategory::where('id', $request->category_id)->exists())
            return response()->json([
                'success' => false,
                'message' => 'دسته بندی به درستی انتخاب نشده است.'
            ]);

        $post = BlogPost::create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'summary' => $request->summary,
            'text' => $request->text,
            'author_id' => Auth::guard('web')->id(),
            'picture_id' => HelperMedia::uploadPicture($request->file('picture'), 'posts/pictures/', 1280, 850),
            'status' => $request->status,
            'language' => $request->language
        ]);

        $post = $post->fresh(['category', 'picture.thumbnail']);

        return response()->json([
            'success' => true,
            'message' => 'پست با موفقیت افزوده شد.',
            'post' => $post,
            'redirect_url' => Route('admin_blog_post_edit', ['id' => $post->id])
        ]);
    }

    public function edit($id, Request $request)
    {
        $post = BlogPost::with(['category', 'picture.thumbnail'])->findOrFail($id);
        $user = $request->user();

        if ($request->isMethod('GET')) {
            $categories = BlogPostCategory::all();

            $page = [
                'title' => 'ویرایش ' . $post->title,
                'description' => 'ویرایش ' . $post->title,
            ];

            return view('admin.blog.add', compact('categories'), compact('post', 'page'));
        }

        $validator = Validator::make(Request()->all(), [
            'title' => 'required|string|max:191',
            'summary' => 'required|string|max:1000',
            'category_id' => 'required|numeric|not_in:0',
            'text' => 'required|string',
            'picture' => 'nullable|mimes:jpeg,jpg,png|max:20000',
            'status' => 'required|in:published,draft',
            'language' => 'required|in:en,fa'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message<br>')
            ]);

        if (!BlogPostCategory::where('id', $request->category_id)->exists())
            return response()->json([
                'success' => false,
                'message' => 'دسته بندی به درستی انتخاب نشده است.'
            ]);

        $post->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'summary' => $request->summary,
            'text' => $request->text,
            'author_id' => $user->id,
            'picture_id' => empty($request->picture) ? $post->picture_id : HelperMedia::uploadPicture($request->file('picture'), 'posts/pictures/', 1280, 850, false, $post->picture),
            'status' => $request->status,
            'language' => $request->language
        ]);

        $post = $post->fresh(['category', 'picture.thumbnail']);

        return response()->json([
            'success' => true,
            'message' => 'پست با موفقیت ویرایش شد.',
            'post' => $post
        ]);
    }
}
