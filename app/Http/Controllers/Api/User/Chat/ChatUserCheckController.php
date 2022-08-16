<?php

namespace App\Http\Controllers\Api\User\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatUserCheckController extends Controller
{
    public function index(Request $request) {
        $user = $request->user('api');

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }
}
