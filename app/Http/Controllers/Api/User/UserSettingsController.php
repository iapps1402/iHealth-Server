<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserSettingsController extends Controller
{
    public function details()
    {
        $lang = Auth::guard('api')->user()->language;
        return response()->json([
            'success' => true,
            'language' => $lang
        ]);
    }

    public function lang(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'language' => 'required|in:en,fa'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message')
            ]);

        Auth::guard('api')->user()->update([
            'language' => $request->language,
        ]);

        Lang::setLocale($request->language);

        return response()->json([
            'success' => true,
            'message' => Lang::get('messages.submitted_successfully')
        ]);
    }
}
