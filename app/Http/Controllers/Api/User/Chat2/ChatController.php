<?php

namespace App\Http\Controllers\Api\User\Chat2;

use App\Events\ChatMessageEvent;
use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatSessionMessage;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function all(Request $request)
    {
        return response()->json([
            'success' => true,
            'messages' => ChatMessage::orderBy('created_at')->get()
        ]);
    }

    public function pv($chatId, Request $request)
    {
        $user = $request->user('api');
        return response()->json([
            'success' => true,
            'messages' => ChatMessage::where(function ($q) use ($user, $chatId) {
                $q->where(function ($q) use ($user, $chatId) {
                    $q->where('user_id', $user->id)
                        ->where('user_to_id', $chatId);
                })->orWhere(function ($q) use ($chatId, $user) {
                    $q->where('user_id', $chatId)
                        ->where('user_to_id', $user->id);
                });
            })
                //  ->where('chat_id', $chatId)
                ->orderBy('created_at')->get()
        ]);
    }
}
