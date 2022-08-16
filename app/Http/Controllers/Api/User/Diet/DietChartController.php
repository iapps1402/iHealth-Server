<?php

namespace App\Http\Controllers\Api\User\Diet;

use App\Http\Controllers\Controller;
use App\Models\UserDateRelation;
use App\Models\UserDietProgram;
use App\Models\UserWeight;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;

class DietChartController extends Controller
{
    public function index($id, Request $request)
    {
        $user = $request->user('api');
        $program = UserDietProgram::with(['user'])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        $axisX = array();
        $calorieAxisY = array();
        $carbsAxisY = array();
        $proteinAxisY = array();
        $fatAxisY = array();
        $weightAxisX = array();
        $weightAxisY = array();

        $i = 0;
        $lastDate = null;
        while ($lastDate == null || $program->created_at < $lastDate) {
            $lastDate = $program->created_at->addDays($program->user->diet_program_period - $i);
            array_push($axisX, Jalalian::fromCarbon($lastDate)->format('m/d'));

            $relation = UserDateRelation::where('user_id', $program->user->id)
                ->whereDate('date', $lastDate)
                ->first();

            array_push($calorieAxisY, $relation == null ? 0 : $relation->food_calories);
            array_push($carbsAxisY, $relation == null ? 0 : $relation->food_carbs);
            array_push($proteinAxisY, $relation == null ? 0 : $relation->food_protein);
            array_push($fatAxisY, $relation == null ? 0 : $relation->food_fat);
            $i++;
        }

        $i = 0;
        $weightLastDate = null;
        while ($weightLastDate == null || $program->created_at < $weightLastDate) {
            $weightLastDate = $program->created_at->addDays($program->user->diet_program_period - $i);
            array_push($weightAxisX, Jalalian::fromCarbon($weightLastDate)->format('m/d'));

            $weight = UserWeight::where('user_id', $user->id)
                ->whereBetween('date', [$weightLastDate->format('Y-m-d'), $weightLastDate->addDays(7)->format('Y-m-d')])
                ->avg('weight');

            if ($weight == null)
                $weight = 0;

            array_push($weightAxisY, $weight);
            $i += 7;
        }

        return response()->json([
            'success' => true,
            'calorie' => [
                'axis_x' => $axisX,
                'axis_y' => $calorieAxisY
            ],
            'carbs' => [
                'axis_x' => $axisX,
                'axis_y' => $carbsAxisY
            ],
            'protein' => [
                'axis_x' => $axisX,
                'axis_y' => $proteinAxisY
            ],
            'fat' => [
                'axis_x' => $axisX,
                'axis_y' => $fatAxisY
            ],
            'weight' => [
                'axis_x' => $weightAxisX,
                'axis_y' => $weightAxisY
            ],
        ]);
    }
}
