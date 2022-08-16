<?php

namespace App\Http\Controllers\Api\User\Food;

use App\Http\Controllers\Controller;
use App\Models\UserFood;
use App\Models\UserDateRelation;
use App\Models\UserFoodData;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FoodCustomController extends Controller
{
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'name' => 'required|string',
            'meal' => 'required|in:breakfast,lunch,dinner,snacks',
            'carbs' => 'required|numeric',
            'fat' => 'required|numeric',
            'protein' => 'required|numeric',
            'fiber' => 'required|numeric'
        ]);
        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message')
            ]);

        $user = $request->user('api');

        if (!$user->profile_completed)
            return response()->json([
                'success' => false,
                'message' => Lang::get('messages.complete_your_profile')
            ]);

        try {
            DB::beginTransaction();

            $relation = UserDateRelation::where('user_id', $user->id)
                ->where('date', $request->date)
                ->first();

            if ($relation == null)
                $relation = UserDateRelation::create([
                    'user_id' => $user->id,
                    'calorie_ratio' => $user->calorie_ratio,
                    'protein_ratio' => $user->protein_ratio,
                    'fat_ratio' => $user->fat_ratio,
                    'fiber_ratio' => $user->fiber_ratio,
                    'date' => $request->date,
                ]);

            $data = UserFoodData::create([
                'name' => $request->name
            ]);

            $userFood = UserFood::create([
                'relation_id' => $relation->id,
                'name_fa' => $request->name,
                'name_en' => $request->name,
                'meal' => $request->meal,
                'protein' => $request->protein,
                'fat' => $request->fat,
                'carbs' => $request->carbs,
                'fiber' => $request->fiber,
                'data_id' => $data->id
            ]);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => Lang::get('messages.error')
            ]);
        }

        $userFood = UserFood::with(['food'])->find($userFood->id);
        $userFood->append(['can_be_edited']);

        $relation = $relation->fresh();

        return response()->json([
            'success' => true,
            'relation' => $relation,
            'food' => $userFood,
            'message' => Lang::get('messages.added_successfully')
        ]);
    }

    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|not_in:0',
            'date' => 'required|date',
            'name' => 'required|string',
            'meal' => 'required|in:breakfast,lunch,dinner,snacks',
            'carbs' => 'required|numeric',
            'fat' => 'required|numeric',
            'protein' => 'required|numeric',
            'fiber' => 'required|numeric'
        ]);
        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message')
            ]);

        $user = $request->user('api');

        if (!$user->profile_completed)
            return response()->json([
                'success' => false,
                'message' => Lang::get('messages.complete_your_profile')
            ]);

        $userFood = UserFood::findOrFail($request->id);

        try {
            DB::beginTransaction();

            $relation = UserDateRelation::where('user_id', $user->id)
                ->where('date', $request->date)
                ->first();

            if ($relation == null)
                $relation = UserDateRelation::create([
                    'user_id' => $user->id,
                    'calorie_ratio' => $user->calorie_ratio,
                    'protein_ratio' => $user->protein_ratio,
                    'fat_ratio' => $user->fat_ratio,
                    'fiber_ratio' => $user->fiber_ratio,
                    'date' => $request->date
                ]);

            $data = UserFoodData::create([
                'name' => $request->name
            ]);

            $userFood->update([
                'relation_id' => $relation->id,
                'name_fa' => $request->name,
                'name_en' => $request->name,
                'meal' => $request->meal,
                'protein' => $request->protein,
                'fat' => $request->fat,
                'carbs' => $request->carbs,
                'fiber' => $request->fiber,
                'data_id' => $data->id,
            ]);

            $userFood->update([
                'name' => $request->name
            ]);

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => Lang::get('messages.error')
            ]);
        }

        $userFood = $userFood->fresh(['food']);
        $userFood->append(['can_be_edited']);

        $relation = $relation->fresh();

        return response()->json([
            'success' => true,
            'relation' => $relation,
            'food' => $userFood,
            'message' => Lang::get('messages.added_successfully')
        ]);
    }
}
