<?php

namespace App\Http\Controllers\Api\User\Food;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\FoodUnit;
use App\Models\UserFood;
use App\Models\UserDateRelation;
use App\Models\UserFoodData;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FoodSingleController extends Controller
{
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'meal' => 'required|in:breakfast,lunch,dinner,snacks',
            'food_id' => 'required|numeric|not_in:0',
            'unit_id' => 'required|numeric|not_in:0',
            'number' => 'required|numeric'
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

        $food = Food::findOrFail($request->food_id);
        $foodUnit = FoodUnit::with(['food'])->find($request->unit_id);

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
                'number' => $request->number,
                'unit_id' => $request->unit_id
            ]);

            $userFood = UserFood::create([
                'relation_id' => $relation->id,
                'food_id' => $food->id,
                'name_fa' => $food->name_fa,
                'name_en' => $food->name_en,
                'meal' => $request->meal,
                'protein' => number_format((float)($request->number * $foodUnit->real_protein), 2, '.', ''),
                'fat' => number_format((float)($request->number * $foodUnit->real_fat), 2, '.', ''),
                'carbs' => number_format((float)($request->number * $foodUnit->real_carbs), 2, '.', ''),
                'fiber' => number_format((float)($request->number * $foodUnit->real_fiber), 2, '.', ''),
                'description_fa' => $request->number . ' ' . $foodUnit->name_fa,
                'description_en' => $request->number . ' ' . $foodUnit->name_en,
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

        $userFood = UserFood::with(['food.units', 'data.unit'])->find($userFood->id);
        $userFood->append(['can_be_edited']);

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
            'id' => 'required|numeric',
            'date' => 'required|date',
            'meal' => 'required|in:breakfast,lunch,dinner,snacks',
            'unit_id' => 'required|numeric|not_in:0',
            'number' => 'required|numeric'
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

        $userFood = UserFood::with(['food', 'relation'])->findOrFail($request->id);
        $food = $userFood->food;

        $foodUnit = FoodUnit::with(['food'])->find($request->unit_id);

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

            $userFood->data->update([
                'number' => $request->number,
                'unit_id' => $request->unit_id
            ]);

            $userFood->update([
                'food_id' => $food->id,
                'name_fa' => $food->name_fa,
                'name_en' => $food->name_en,
                'meal' => $request->meal,
                'protein' => number_format((float)($request->number * $foodUnit->real_protein), 2, '.', ''),
                'fat' => number_format((float)($request->number * $foodUnit->real_fat), 2, '.', ''),
                'carbs' => number_format((float)($request->number * $foodUnit->real_carbs), 2, '.', ''),
                'fiber' => number_format((float)($request->number * $foodUnit->real_fiber), 2, '.', ''),
                'description_fa' => $request->number . ' ' . $foodUnit->name_fa,
                'description_en' => $request->number . ' ' . $foodUnit->name_en,
                'relation_id' => $relation->id
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

        $userFood = $userFood->fresh(['food.units', 'data.unit']);
        $userFood->append(['can_be_edited']);

        return response()->json([
            'success' => true,
            'relation' => $userFood->relation,
            'food' => $userFood,
            'message' => Lang::get('messages.edited_successfully')
        ]);
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|not_in:0'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message')
            ]);

        $user = $request->user('api');

        $food = UserFood::where('id', $request->id)
            ->whereHas('relation', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->first();

        if ($food == null)
            return response()->json([
                'success' => false,
                'message' => Lang::get('messages.not_found')
            ]);

        $food->delete();

        return response()->json([
            'success' => true,
            'message' => Lang::get('messages.deleted_successfully')
        ]);
    }


    public function similarities($id, Request $request)
    {
        $food = Food::findOrFail($id);

        $similarities = $food->similarities->inRandomOrder()->take(8)->get();

        foreach ($similarities as $similarity)
            $similarity->append(['default_unit']);

        return response()->json([
            'success' => true,
            'foods' => $similarities
        ]);
    }
}
