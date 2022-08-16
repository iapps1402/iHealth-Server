<?php

namespace App\Http\Controllers\Api\User\Diet;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\UserDietProgramDayMeal;
use App\Models\UserDietProgramDayMealItem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class DietMealItemController extends Controller
{
    public function changeFood($programId, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|not_in:0',
            'food_id' => 'required|numeric|not_in:0',
            'force' => 'required|boolean'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(":message\n")
            ]);
        $user = $request->user('api');

        $food = Food::findOrFail($request->food_id);
        $food->append('default_unit');

        $item = UserDietProgramDayMealItem::with(['food', 'unit'])->whereHas('meal.day.program', function ($q) use ($user, $programId) {
            $q->where('user_id', $user->id)
                ->where('program_id', $programId);
        })->findOrFail($request->id);

        if ($food->id == $item->food_id)
            return response()->json([
                'success' => false,
                'message' => 'Invalid input!'
            ]);

        $oldProtein = $item->value * $item->unit->real_protein;
        $oldCalorie = $item->value * $item->unit->real_calorie;

        try {
            $newValueProtein = $oldProtein / (double)$food->default_unit->real_protein;
        } catch (Exception $e) {
            $newValueProtein = PHP_INT_MAX;
        }
        try {
            $newValueCalorie = $oldCalorie / (double)$food->default_unit->real_calorie;
        } catch (Exception $exception) {
            $newValueCalorie = PHP_INT_MAX;
        }

        $newValue = min($newValueCalorie, $newValueProtein);
        if ($newValue == PHP_INT_MAX)
            $newValue = 0;

        $newValue = round($newValue * 2) / 2;

        if (!$request->force) {

            if ($newValue <= 0)
                return response()->json([
                    'success' => false,
                    'message' => Lang::get('messages.diet_meal_item_controller_message_2')
                ]);

            $message = Lang::get('messages.diet_meal_item_controller_message');
            $message = str_replace(':value', $newValue . ' ' . $food->default_unit->title . ' ' . $food->title, $message);
            $message = str_replace(':calorie', ($newValue * $food->default_unit->real_calorie), $message);
            $message = str_replace(':protein', ($newValue * $food->default_unit->real_protein), $message);

            return response()->json([
                'success' => true,
                'confirmed' => false,
                'message' => $message
            ]);
        }

        $item->update([
            'value' => $newValue,
            'food_id' => $food->id,
            'unit_id' => $food->default_unit->id
        ]);

        $item = $item->fresh(['food.picture.thumbnail', 'unit']);

        return response()->json([
            'success' => true,
            'confirmed' => true,
            'message' => Lang::get('messages.edited_successfully'),
            'item' => $item
        ]);
    }

    public function changeValue($programId, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|not_in:0',
            'value' => 'nullable|numeric|not_in:0',
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(":message\n")
            ]);
        $user = $request->user('api');

        if ($request->value <= 0)
            return response()->json([
                'success' => false,
                'message' => 'Invalid input.'
            ]);

        $item = UserDietProgramDayMealItem::with(['food', 'unit'])->whereHas('meal.day.program', function ($q) use ($user, $programId) {
            $q->where('user_id', $user->id)
                ->where('program_id', $programId);
        })->findOrFail($request->id);

        $item->update([
            'value' => $request->value
        ]);

        $item = $item->fresh(['food.picture.thumbnail', 'unit']);

        return response()->json([
            'success' => true,
            'message' => Lang::get('messages.edited_successfully'),
            'item' => $item
        ]);
    }

    public function remove($programId, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|not_in:0'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(":message\n")
            ]);

        $user = $request->user('api');

        UserDietProgramDayMealItem::with(['food', 'unit'])->whereHas('meal.day.program', function ($q) use ($user, $programId) {
            $q->where('user_id', $user->id)
                ->where('program_id', $programId);
        })->findOrFail($request->id)->delete();

        return response()->json([
            'success' => true,
            'message' => Lang::get('messages.deleted_successfully')
        ]);
    }

    public function add($programId, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'food_id' => 'required|numeric|not_in:0',
            'meal_id' => 'required|numeric|not_in:0',
            'value' => 'nullable|numeric|not_in:0',
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(":message\n")
            ]);
        $user = $request->user('api');

        UserDietProgramDayMeal::whereHas('day.program', function ($q) use ($user, $programId) {
            $q->where('user_id', $user->id)
                ->where('program_id', $programId);
        })->findOrFail($request->meal_id);

        $food = Food::findOrFail($request->food_id);

        $item = UserDietProgramDayMealItem::create([
            'meal_id' => $request->meal_id,
            'food_id' => $request->food_id,
            'unit_id' => $food->default_unit->id,
            'value' => $request->value
        ]);

        $item = $item->fresh(['unit', 'food.picture.thumbnail']);

        return response()->json([
            'success' => true,
            'message' => Lang::get('messages.added_successfully'),
            'item' => $item
        ]);
    }
}
