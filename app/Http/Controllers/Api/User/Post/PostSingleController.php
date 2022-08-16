<?php

namespace App\Http\Controllers\Api\User\Post;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class PostSingleController extends Controller
{
    public function index($id)
    {
        $post = BlogPost::with(['category', 'author', 'picture'])->where('id', $id)
            ->where('status', 'published')->first();

        if ($post == null)
            return response()->json([
                'success' => false,
                'message' => Lang::get('messages.not_found')
            ]);

        $post->update([
            'views' => $post->views + 1
        ]);

        return view('api.post', compact('post'))->with('lang', $post->language);
    }
}
