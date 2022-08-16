<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodCookingIngredient extends Model
{
    protected $table = 'food_cooking_ingredients';
    protected $fillable = ['cooking_id', 'name_fa', 'value_fa', 'name_en', 'value_en'];

    public function cooking()
    {
        return $this->belongsTo(FoodCooking::class, 'cooking_id');
    }
}
