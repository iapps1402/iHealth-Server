<?php

namespace App\Http\Controllers\Api\User\Main;

use App\Http\Controllers\Controller;
use App\Models\UserDateRelation;
use App\Models\UserWeight;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Morilog\Jalali\Jalalian;

class MainBlockController extends Controller
{
    public function block1(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date'
        ]);

        if ($validator->fails())
            return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->all(':message')
                ]
            );

        $user = $request->user('api');

        $relation = UserDateRelation::where('date', $request->date)
            ->where('user_id', $user->id)
            ->first();

        $fatSum = 0;
        $proteinSum = 0;
        $foodCalories = 0;
        $carbsSum = 0;
        $activitySum = 0;
        $fiberSum = 0;

        if ($relation != null) {
            $activitySum = $relation->activities()->sum('calorie_ratio');

            foreach ($relation->foods as $food) {
                $fatSum += $food->fat;
                $proteinSum += $food->protein;
                $foodCalories += $food->calorie;
                $carbsSum += $food->carbs;
                $fiberSum += $food->fiber;
            }
        }

        return response()->json([
            'success' => true,
            'fat_ratio' => $relation == null ? 0 : $relation->real_fat_ratio,
            'fat_sum' => $fatSum,
            'protein_ratio' => $relation == null ? 0 : $relation->real_protein_ratio,
            'protein_sum' => $proteinSum,
            'calorie_ratio' => $relation == null ? 0 : $relation->real_calorie_ratio,
            'food_calories' => $foodCalories,
            'carbs_ratio' => $relation == null ? 0 : $relation->real_carbs_ratio,
            'carbs_sum' => $carbsSum,
            'activity_sum' => $activitySum,
            'fiber_ratio' => $relation == null ? 0 : $relation->fiber_ratio,
            'fiber_sum' => $fiberSum,
            'remaining_calories' => $relation == null ? 0 : $relation->remaining_calories
        ]);
    }

    public function block2(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date'
        ]);

        if ($validator->fails())
            return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->all(':message')
                ]
            );

        $user = $request->user('api');

        $axisX = array();
        $axisY = array();
        for ($i = 0; $i < 7; $i++) {
            array_push($axisX, Jalalian::fromCarbon(now())->addDays(-$i)->format('y/m/d'));
            $relation = UserDateRelation::where('user_id', $user->id)
                ->whereDate('date', Carbon::parse(now())->addDays(-$i))
                ->first();

            array_push($axisY, $relation == null ? 0 : $relation->food_calories);
        }
        return response()->json([
            'success' => true,
            'axis_x' => $axisX,
            'axis_y' => $axisY
        ]);
    }

    public function block3(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message')
            ]);

        $user = $request->user('api');
        $currentWeight = UserWeight::where('user_id', $user->id)->orderByDesc('created_at')->first();
        $primaryWeight = UserWeight::where('user_id', $user->id)->orderBy('created_at')->first();

        return response()->json([
            'success' => true,
            'primary_weight' => $primaryWeight,
            'current_weight' => $currentWeight,
            'goal_weight' => $user->goal_weight,
        ]);
    }
}
