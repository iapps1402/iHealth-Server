<?php

namespace App\Http\Controllers\Api\User\Weight;

use App\Http\Controllers\Controller;
use App\Models\UserWeight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class WeightIndexController extends Controller
{
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'weight' => 'required|numeric|not_in:0',
            'date' => 'required|date'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message')
            ]);

        if ($request->weight < 10)
            return response()->json([
                'success' => false,
                'message' => Lang::get('messages.weight_must_be_greater_than_10')
            ]);

        $user = Auth::guard('api')->user();

        if (is_null($user->goal_weight))
            return response()->json([
                'success' => false,
                'message' => Lang::get('messages.you_should_define_goal_weight')
            ]);

        UserWeight::whereDate('date', $request->date)->updateOrCreate([
            'user_id' => $user->id
        ], [
            'weight' => $request->weight,
            'date' => $request->date
        ]);

        $currentWeight = UserWeight::where('user_id', $user->id)->orderByDesc('created_at')->first();
        $primaryWeight = UserWeight::where('user_id', $user->id)->orderBy('created_at')->first();
        return response()->json([
            'success' => true,
            'primary_weight' => $primaryWeight,
            'current_weight' => $currentWeight,
            'goal_weight' => $user->goal_weight,
            'message' => Lang::get('messages.added_successfully')
        ]);
    }
}
