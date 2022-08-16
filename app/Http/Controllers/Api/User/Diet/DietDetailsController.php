<?php

namespace App\Http\Controllers\Api\User\Diet;

use App\Http\Controllers\Controller;
use App\Models\UserDietProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class DietDetailsController extends Controller
{
    public function index($id, Request $request)
    {
        $program = UserDietProgram::with(['user', 'days.meals.items.unit', 'days.meals.items.food.picture.thumbnail', 'supplements.supplement', 'supplements.unit'])->findOrFail($id);

        if ($program->user_read_at == null)
            $program->update([
                'user_read_at' => now()
            ]);

        $calorie = 0;
        $protein = 0;
        $numberOfDays = 0;

        foreach ($program->days as $day) {
            foreach ($day->meals as $meal)
                foreach ($meal->items as $item) {
                    $calorie += $item->value * $item->unit->real_calorie;
                    $protein += $item->value * $item->unit->real_protein;
                }
            $numberOfDays++;
        }

        if ($numberOfDays == 0)
            return response()->json([
                'success' => false,
                'message' => 'Error occurred.'
            ]);

        $calorie /= (double)$numberOfDays;
        $protein /= (double)$numberOfDays;
        $calorie = (int)$calorie;
        $protein = (int)$protein;

        $message = "";

        if (
            $protein == 0 ||
            $program->protein < $protein && $protein / (double)$program->protein > 1.1 ||
            $program->protein > $protein && $program->protein / (double)$protein < .1
        ) {
            $message = Lang::get('messages.diet_protein_issue') . "\n";
            $message = str_replace(':avg', number_format($protein), $message);
            $message = str_replace(':protein', number_format($program->protein), $message);
        } else if (
            $program->calorie < $calorie && $calorie / (double)$program->calorie > 1.1 ||
            $program->calorie > $calorie && $program->calorie / (double)$calorie < .1
        ) {
            $message .= Lang::get('messages.diet_calorie_issue');
            $message = str_replace(':avg', number_format($calorie), $message);
            $message = str_replace(':calorie', number_format($program->calorie), $message);
        }

        return response()->json([
            'success' => true,
            'program' => $program,
            'message' => $message == null ? null : [
                'title' => Lang::get('messages.system_message'),
                'text' => $message
            ]
        ]);
    }
}
