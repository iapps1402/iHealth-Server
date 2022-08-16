<?php

namespace App\Http\Controllers\Api\User\Weight;

use App\Http\Controllers\Controller;
use App\Models\UserWeight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class WeightSettingsController extends Controller
{
    private $user;

    function __construct()
    {
        $this->user = Auth::guard('api')->user();
    }

    public function details(Request $request)
    {
        $current = UserWeight::where('user_id', $this->user->id)->orderByDesc('created_at')->first();
        return response()->json([
            'success' => true,
            'goal' => $this->user->goal_weight,
            'current' => $current == null ? null : $current->weight
        ]);
    }

    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current' => 'required|numeric|not_in:0',
            'goal' => 'required|numeric|not_in:0'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all()
            ]);

        if ($request->current < 0 || $request->goal < 0)
            return response()->json([
                'success' => false,
                'message' => Lang::get('messages.error')
            ]);

        if($request->current == $request->goal)
            return response()->json([
                'success' => false,
                'message' => Lang::get('messages.current_and_goal_weight_can_not_be_the_same')
            ]);

        try {
            DB::beginTransaction();
            if ($request->goal != $this->user->goal_weight)
                $this->user->update([
                    'goal_weight' => $request->goal
                ]);

            $currentExists = UserWeight::where('user_id', $this->user->id)
                ->orderByDesc('created_at')->first();

            $currentExists = $currentExists != null && $currentExists->weight == $request->weight;

            if (!$currentExists || $request->goal != $this->user->goal_weight)
                UserWeight::where('user_id', $this->user->id)->delete();

            if (!$currentExists)
                UserWeight::create([
                    'user_id' => $this->user->id,
                    'date' => now()->format('Y-m-d'),
                    'weight' => $request->current
                ]);

            DB::commit();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => Lang::get('messages.error')
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => Lang::get('messages.edited_successfully')
        ]);
    }
}
