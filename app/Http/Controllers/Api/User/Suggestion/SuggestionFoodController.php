<?php

namespace App\Http\Controllers\Api\User\Suggestion;

use App\Http\Controllers\Controller;
use App\Models\FoodSuggestion;
use App\Models\UserDateRelation;
use App\Models\UserFood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class SuggestionFoodController extends Controller
{
    protected $user;

    public function __construct()
    {
        ;
        $this->user = Request()->user('api');
        $this->user->append(['profile_completed']);
    }

    public function index(Request $request)
    {
        $this->user->append(['has_food_Suggestion']);
        $todayCalorie = UserFood::whereHas('relation', function ($q) {
            $q->whereDate('date', now())
                ->where('user_id', $this->user->id);
        })->sum(DB::raw('9*fat + 4*protein + 4*carbs'));

        $todayProtein = UserFood::whereHas('relation', function ($q) {
            $q->whereDate('date', now())
                ->where('user_id', $this->user->id);
        })->sum('protein');

//        if ($todayCalorie >= $this->user->calorie_ratio || ($todayCalorie / (double)$this->user->calorie_ratio) * 100 <= 4)
//            return response()->json([
//                'success' => false,
//                'message' => Lang::get('messages.user_cant_have_suggestion')
//            ]);

        $relation = UserDateRelation::whereDate('date', now())
            ->where('user_id', $this->user->id)
            ->first();

        $totalFatSum = 0;
        $totalProteinSum = 0;
        $totalFoodCalories = 0;
        $totalCarbsSum = 0;
        $totalFiberSum = 0;
        $totalCalorieSum = 0;

        $breakfastFatSum = 0;
        $breakfastProteinSum = 0;
        $breakfastFoodCalories = 0;
        $breakfastCarbsSum = 0;
        $breakfastFiberSum = 0;
        $breakfastCalorieSum = 0;

        $lunchFatSum = 0;
        $lunchProteinSum = 0;
        $lunchFoodCalories = 0;
        $lunchCarbsSum = 0;
        $lunchFiberSum = 0;
        $lunchCalorieSum = 0;

        $dinnerFatSum = 0;
        $dinnerProteinSum = 0;
        $dinnerFoodCalories = 0;
        $dinnerCarbsSum = 0;
        $dinnerFiberSum = 0;
        $dinnerCalorieSum = 0;

        $snacksFatSum = 0;
        $snacksProteinSum = 0;
        $snacksFoodCalories = 0;
        $snacksCarbsSum = 0;
        $snacksFiberSum = 0;
        $snacksCalorieSum = 0;

        if ($relation != null) {
            foreach ($relation->foods as $food) {
                $totalFatSum += $food->fat;
                $totalProteinSum += $food->protein;
                $totalFoodCalories += $food->calorie;
                $totalCarbsSum += $food->carbs;
                $totalFiberSum += $food->fiber;
            }

            foreach ($relation->foods()->where('meal', 'breakfast')->get() as $food) {
                $breakfastFatSum += $food->fat;
                $breakfastProteinSum += $food->protein;
                $breakfastFoodCalories += $food->calorie;
                $breakfastCarbsSum += $food->carbs;
                $breakfastFiberSum += $food->fiber;
            }

            foreach ($relation->foods()->where('meal', 'lunch')->get() as $food) {
                $lunchFatSum += $food->fat;
                $lunchProteinSum += $food->protein;
                $lunchFoodCalories += $food->calorie;
                $lunchCarbsSum += $food->carbs;
                $lunchFiberSum += $food->fiber;
            }

            foreach ($relation->foods()->where('meal', 'dinner')->get() as $food) {
                $dinnerFatSum += $food->fat;
                $dinnerProteinSum += $food->protein;
                $dinnerFoodCalories += $food->calorie;
                $dinnerCarbsSum += $food->carbs;
                $dinnerFiberSum += $food->fiber;
            }

            foreach ($relation->foods()->where('meal', 'snacks')->get() as $food) {
                $snacksFatSum += $food->fat;
                $snacksProteinSum += $food->protein;
                $snacksFoodCalories += $food->calorie;
                $snacksCarbsSum += $food->carbs;
                $snacksFiberSum += $food->fiber;
            }

            $totalCalorieSum = 4 * $totalProteinSum + $totalCarbsSum * 4 + 9 * $totalFatSum;
            $breakfastCalorieSum = 4 * $breakfastProteinSum + $breakfastCarbsSum * 4 + 9 * $breakfastFatSum;
            $lunchCalorieSum = 4 * $lunchProteinSum + $lunchCarbsSum * 4 + 9 * $lunchFatSum;
            $dinnerCalorieSum = 4 * $dinnerProteinSum + $dinnerCarbsSum * 4 + 9 * $dinnerFatSum;
            $snacksCalorieSum = 4 * $snacksProteinSum + $snacksCarbsSum * 4 + 9 * $snacksFatSum;
            $calorieRatio = $relation->real_calorie_ratio;
            $proteinRatio = $relation->real_protein_ratio;
        } elseif ($this->user->profile_completed) {
            $calorieRatio = $this->user->calorie_ratio;
            $proteinRatio = $this->user->protein_ratio;
        } else {
            $calorieRatio = 0;
            $proteinRatio = 0;
        }

        $notice = null;
        $noticeReason = null;
        $suggestions = [];

        if ($totalCalorieSum == 0)
            $notice = Lang::get('messages.notice_add_your_foods');

        if ($this->user->has_food_suggestion) {
            $neededProtein = (int)($proteinRatio - $todayProtein);
            $neededProteinPercentage = ($neededProtein / (double)$proteinRatio) * 100;

            $neededCalorie = (int)($calorieRatio - $todayCalorie);
            $neededCaloriePercentage = ($neededCalorie / (double)$calorieRatio) * 100;

            if ($neededCalorie < 0) {
                if (abs($neededCaloriePercentage) > 8) {
                    $notice = Lang::get('messages.notice_consume_less_calorie');
                    $notice = str_replace(':extra_calorie', abs($neededCalorie), $notice);
                }
            } elseif ($neededProtein < 0 && abs($neededProteinPercentage) > 4) {
                $notice = Lang::get('messages.notice_consume_less_protein');
                $notice = str_replace(':extra_protein', abs($neededProtein), $notice);
            } else if ($neededProtein > 0 && abs($neededProteinPercentage) > 4) {
                $notice = Lang::get('messages.notice_consume_extra_protein');
                $notice = str_replace(':needed_protein', abs($neededProtein), $notice);
                $suggestions = FoodSuggestion::with(['food.picture', 'food.units', 'unit'])->inRandomOrder()->take(12)->get();
            } else if (abs($neededCaloriePercentage) > 8) {
                $notice = Lang::get('messages.notice_consume_extra_calorie');
                $notice = str_replace(':needed_calorie', abs($neededCalorie), $notice);
            }
        }

        return response()->json([
                'success' => true,
                'calorie_ratio' => $calorieRatio,
                'protein_ratio' => $proteinRatio,
                'today_calorie' => $todayCalorie,
                'today_protein' => $todayProtein,
                'relation' => $relation,
                'notice' => $notice,
                'notice_reason' => $noticeReason,
                'suggestions' => $suggestions,
                'user' => $this->user,
                'total_sum' => [
                    'calorie' => $relation == null ? 0 : $totalCalorieSum,
                    'fat' => $relation == null ? 0 : $totalFatSum,
                    'protein' => $relation == null ? 0 : $totalProteinSum,
                    'carbs' => $relation == null ? 0 : $totalCarbsSum,
                    'fiber' => $relation == null ? 0 : $totalFiberSum,
                ],
                'breakfast_sum' => [
                    'calorie' => $relation == null ? 0 : $breakfastCalorieSum,
                    'fat' => $relation == null ? 0 : $breakfastFatSum,
                    'protein' => $relation == null ? 0 : $breakfastProteinSum,
                    'carbs' => $relation == null ? 0 : $breakfastCarbsSum,
                    'fiber' => $relation == null ? 0 : $breakfastFiberSum,
                ],
                'lunch_sum' => [
                    'calorie' => $relation == null ? 0 : $lunchCalorieSum,
                    'fat' => $relation == null ? 0 : $lunchFatSum,
                    'protein' => $relation == null ? 0 : $lunchProteinSum,
                    'carbs' => $relation == null ? 0 : $lunchCarbsSum,
                    'fiber' => $relation == null ? 0 : $lunchFiberSum,
                ],
                'dinner_sum' => [
                    'calorie' => $relation == null ? 0 : $dinnerCalorieSum,
                    'fat' => $relation == null ? 0 : $dinnerFatSum,
                    'protein' => $relation == null ? 0 : $dinnerProteinSum,
                    'carbs' => $relation == null ? 0 : $dinnerCarbsSum,
                    'fiber' => $relation == null ? 0 : $dinnerFiberSum,
                ],
                'snacks_sum' => [
                    'calorie' => $relation == null ? 0 : $snacksCalorieSum,
                    'fat' => $relation == null ? 0 : $snacksFatSum,
                    'protein' => $relation == null ? 0 : $snacksProteinSum,
                    'carbs' => $relation == null ? 0 : $snacksCarbsSum,
                    'fiber' => $relation == null ? 0 : $snacksFiberSum,
                ],
                'date' => now()->format('Y-m-d')
            ]
        );
    }
}
