<?php

namespace App\Http\Controllers\Api\User\Firebase;

use App\Http\Controllers\Controller;
use App\Models\UserFirebaseToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FirebaseController extends Controller
{
    public function getToken(Request $request)
    {
        if (Validator::make($request->all(), [
            'firebase_token' => 'required|string|min:100|max:255'
        ])->fails())
            return response()->json([
                'success' => false,
                'message' => 'فیلد firebase_token به درستی وارد نشده است.'
            ]);

        $user = Auth::guard('api')->user();

        UserFirebaseToken::create([
            'user_id' => $user->id,
            'token' => $request->firebase_token,
        ]);

        return response()->json([
            'success' => true
        ]);
    }
}
