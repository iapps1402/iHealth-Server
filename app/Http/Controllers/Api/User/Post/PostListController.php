<?php

namespace App\Http\Controllers\Api\User\Post;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostListController extends Controller
{
    function index()
    {
        $user = Auth::guard('api')->user();
        return BlogPost::with(['category', 'author', 'picture'])
            ->where('status', 'published')->orderByDesc('created_at')
            ->where('language', $user->language)
            ->paginate(5);
    }
}
