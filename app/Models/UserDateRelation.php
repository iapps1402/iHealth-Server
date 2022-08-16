<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed foods
 * @property mixed calorie_ratio
 * @property mixed protein_ratio
 * @property mixed fat_ratio
 * @property mixed food_calories
 * @property mixed carbs_ratio
 */
class UserDateRelation extends Model
{
    protected $table = 'user_date_relations';
    protected $fillable = ['user_id', 'date', 'protein_ratio', 'fat_ratio', 'calorie_ratio', 'fiber_ratio'];
    protected $appends = ['carbs_ratio', 'real_calorie_ratio', 'real_carbs_ratio', 'real_protein_ratio', 'real_fat_ratio'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function foods()
    {
        return $this->hasMany(UserFood::class, 'relation_id', 'id');
    }

    public function getFoodCaloriesAttribute()
    {
        $sum = 0.0;
        foreach ($this->foods as $food)
            $sum += $food->calorie;

        return $sum;
    }

    public function getFoodCarbsAttribute()
    {
        $sum = 0.0;
        foreach ($this->foods as $food)
            $sum += $food->carbs;

        return $sum;
    }

    public function getFoodFatAttribute()
    {
        $sum = 0.0;
        foreach ($this->foods as $food)
            $sum += $food->fat;

        return $sum;
    }

    public function getFoodProteinAttribute()
    {
        $sum = 0.0;
        foreach ($this->foods as $food)
            $sum += $food->protein;

        return $sum;
    }

    public function getRemainingCaloriesAttribute()
    {
        return $this->calorie_ratio - $this->food_calories + $this->activities()->sum('calorie_ratio');
    }

    public function getCarbsRatioAttribute()
    {
        return max((($this->calorie_ratio + $this->activities()->sum('calorie_ratio')) - (4 * $this->protein_ratio) - (9 * $this->fat_ratio)) / 4.0, 0);
    }

    public function activities()
    {
        return $this->hasMany(UserActivity::class, 'relation_id', 'id');
    }

    public function getRealCalorieRatioAttribute()
    {
        return $this->activities()->sum('calorie_ratio') + $this->calorie_ratio;
    }

    public function getRealCarbsRatioAttribute()
    {
        $activityCalorieRatio = $this->activities()->sum('calorie_ratio');

        if($activityCalorieRatio == 0)
            return $this->carbs_ratio;

        $carbsDivider = $this->calorie_ratio / 4.0;
        $carbsPercent = (int)$this->carbs_ratio / $carbsDivider;

        $sharedCalorie = $activityCalorieRatio * $carbsPercent;
        return number_format($this->carbs_ratio + ($sharedCalorie / 4.0), 2, '.', '');

    }

    public function getRealProteinRatioAttribute()
    {
        $activityCalorieRatio = $this->activities()->sum('calorie_ratio');

        if($activityCalorieRatio == 0)
            return $this->protein_ratio;

        $proteinDivider = $this->calorie_ratio / 4.0;
        $proteinPercent = (int)$this->protein_ratio / $proteinDivider;

        $sharedCalorie = $activityCalorieRatio * $proteinPercent;
        return number_format($this->protein_ratio + ($sharedCalorie / 4.0), 2, '.', '');

    }

    public function getRealFatRatioAttribute()
    {
        $activityCalorieRatio = $this->activities()->sum('calorie_ratio');

        if($activityCalorieRatio == 0)
            return $this->fat_ratio;

        $fatDivider = $this->calorie_ratio / 9.0;
        $fatPercent = (int)$this->fat_ratio / $fatDivider;

        $sharedCalorie = $activityCalorieRatio * $fatPercent;
        return number_format((float)$this->fat_ratio + ($sharedCalorie / 9.0), 2, '.', '');

    }
}
