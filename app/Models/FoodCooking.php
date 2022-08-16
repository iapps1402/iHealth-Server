<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodCooking extends Model
{
    protected $table = 'food_cookings';
    protected $fillable = ['amount_fa', 'amount_en', 'calorie', 'time'];

    public function food()
    {
        return $this->belongsTo(Food::class, 'id', 'cooking_id');
    }

    public function instructions()
    {
        return $this->hasMany(FoodCookingInstruction::class, 'cooking_id');
    }

    public function ingredients()
    {
        return $this->hasMany(FoodCookingIngredient::class, 'cooking_id');
    }
}
