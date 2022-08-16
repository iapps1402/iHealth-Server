<?php

namespace App\Http\Controllers\Api\User\Chat;

use App\Http\Controllers\Controller;
use App\Models\ChatSession;
use Illuminate\Http\Request;

class ChatSessionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user('api');

        return ChatSession::where('user1_id', $user->id)
            ->orWhere('user2_id', $user->id)
            ->orderByDesc('created_at')
            ->get();
    }
}
