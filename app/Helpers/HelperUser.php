<?php


namespace App\Helpers;


class HelperUser
{
    public static function getActivityCalorieRatio($met, $weightInKilograms, $minutes): int
    {
        return (int)((($met * 3.5 * $weightInKilograms) / 200) * $minutes);
    }
}
