<?php


namespace App\Helpers;


use App\Models\User;
use App\Models\UserFood;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HelperFood
{

    public static function getCalorie($protein, $fat, $carbs)
    {
        return ($protein * 4) + ($fat * 9) + ($carbs * 4);
    }

    public static function canHaveSuggestion(User $user)
    {
        $todayCalorie = UserFood::whereHas('relation', function ($q) use ($user) {
            $q->whereDate('date', now())
                ->where('user_id', $user->id);
        })->sum(DB::raw('9*fat + 4*protein + 4*carbs'));

        if ($todayCalorie == 0 || $todayCalorie >= $user->calorie_ratio || ($todayCalorie / (double)$user->calorie_ratio) * 100 <= 4)
            return false;

        return
            Carbon::parse('21:24:00') <= now() && now() <= Carbon::parse('23:59:59');
    }
}
