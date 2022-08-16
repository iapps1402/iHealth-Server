<?php

namespace App\Http\Controllers\Api\User\Chat2;

use App\Events\ChatMessageEvent;
use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatSendController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string',
            'to' => 'required'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => 'required|string'
            ]);

        $user = $request->user('api');

        if ($user->id == $request->to)
            return response()->json([
                'success' => false,
            ]);

        $message = ChatMessage::create([
            'user_id' => $user->id,
            'message' => $request->message,
            'user_to_id' => $request->to
        ]);

        $message = $message->fresh(['user']);

        event(new ChatMessageEvent($message));
        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function upload(Request $request)
    {
        $file = $request->file('file');
        $file->store('camera/');
        return response()->json([
            'success' => true
        ]);
    }
}
