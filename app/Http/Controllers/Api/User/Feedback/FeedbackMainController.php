<?php

namespace App\Http\Controllers\Api\User\Feedback;

use App\Http\Controllers\Controller;
use App\Models\UserAppRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class FeedbackMainController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user('api');
        $validator = Validator::make($request->all(), [
            'comment' => 'nullable|string|max:1000',
            'rating' => 'required|numeric'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message' . "\n")
            ]);

        UserAppRating::create([
            'user_id' => $user->id,
            'comment' => $request->comment,
            'rating' => $request->rating
        ]);

        return response()->json([
            'success' => true,
            'message' => Lang::get('messages.successfully_added')
        ]);
    }
}
