<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\UserDateRelation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    public function details()
    {
        $user = Auth::guard('api')->user();
        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'birth_date' => 'required|date',
            'weight' => 'required|numeric|between:10,200',
            'height' => 'required|numeric|between:80,2500',
            'gender' => 'required|in:male,female',
            'activity' => 'required|in:no_physical_activity,sedentary,somehow_active,active,very_active,no_physical_activity',
            'fat_ratio' => 'required|numeric|not_in:0',
            'protein_ratio' => 'required|numeric|not_in:0',
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'decrease_or_increase_coefficient' => 'required|numeric'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message')
            ]);

        $user = Auth::guard('api')->user();

        try {
            DB::beginTransaction();
            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'weight' => $request->weight,
                'height' => $request->height,
                'gender' => $request->gender,
                'activity' => $request->activity,
                'fat_ratio' => $request->fat_ratio,
                'protein_ratio' => $request->protein_ratio,
                'birth_date' => $request->birth_date,
                'decrease_or_increase_coefficient' => $request->decrease_or_increase_coefficient,
                'calorie' => null
            ]);

            $relation = UserDateRelation::where('user_id', $user->id)
                ->whereDate('date', now())
                ->first();

            if ($relation != null)
                $relation->update([
                    'calorie_ratio' => $user->calorie_ratio,
                    'protein_ratio' => $user->protein_ratio,
                    'fat_ratio' => $user->fat_ratio,
                    'fiber_ratio' => $user->fiber_ratio,
                ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => Lang::get('messages.error')
            ]);
        }

        $user = $user->fresh();
        $user->append(['age', 'profile_completed', 'can_be_invited', 'has_food_suggestion']);

        return response()->json([
            'success' => true,
            'user' => $user,
            'message' => Lang::get('messages.submitted_successfully')
        ]);
    }
}
