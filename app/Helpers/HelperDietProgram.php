<?php

namespace App\Helpers;

use App\Models\DietProgram;
use App\Models\UserDietProgram;
use App\Models\UserDietProgramDay;
use App\Models\UserDietProgramDayMeal;
use App\Models\UserDietProgramDayMealItem;
use App\Models\UserDietProgramSupplement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class HelperDietProgram
{
    public static function copy(DietProgram $program)
    {
        try {
            DB::beginTransaction();
            $userProgram = UserDietProgram::create([
                'user_id' => $program->user_id,
                'note' => $program->note,
                'protein' => $program->protein,
                'carbs' => $program->carbs,
                'fat' => $program->fat,
                'user_read_at' => $program->user_read_at,
                'writer_id' => $program->writer_id,
                'decrease_or_increase_coefficient' => $program->decrease_or_increase_coefficient,
                'diet_id' => $program->id,
                'created_at' => $program->created_at
            ]);

            foreach ($program->days as $day) {
                $userDay = UserDietProgramDay::create([
                    'day' => $day->day,
                    'program_id' => $userProgram->id
                ]);
                foreach ($day->meals as $meal) {
                    $userMeal = UserDietProgramDayMeal::create([
                        'name_fa' => $meal->name_fa,
                        'name_en' => $meal->name_en,
                        'day_id' => $userDay->id,
                        'icon' => $meal->icon
                    ]);
                    foreach ($meal->items as $item) {
                        UserDietProgramDayMealItem::create([
                            'value' => $item->value,
                            'unit_id' => $item->unit_id,
                            'food_id' => $item->food_id,
                            'meal_id' => $userMeal->id
                        ]);
                    }
                }
            }

            foreach ($program->supplements as $supplement) {
                UserDietProgramSupplement::create([
                    'value' => $supplement->value,
                    'program_id' => $userProgram->id,
                    'supplement_id' => $supplement->supplement_id,
                    'unit_id' => $supplement->unit_id,
                    'text' => $supplement->text,
                    'unit_text' => $supplement->unit_text,
                ]);
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::debug($exception->getMessage());

            throw new Exception('something went wrong!');
        }

        return $userProgram;
    }
}
