<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed food_id
 * @property Food food
 */
class DietProgramDayMealItem extends Model
{
    protected $table = 'diet_program_day_meal_items';
    protected $fillable = ['value', 'unit_id', 'food_id', 'meal_id'];

    public function meal()
    {
        return $this->belongsTo(DietProgramDayMeal::class, 'meal_id');
    }

    public function unit()
    {
        return $this->belongsTo(FoodUnit::class, 'unit_id');
    }

    public function food()
    {
        return $this->belongsTo(Food::class, 'food_id');
    }
}
