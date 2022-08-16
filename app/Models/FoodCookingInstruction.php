<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodCookingInstruction extends Model
{
    protected $table = 'food_cooking_instructions';
    protected $fillable = ['cooking_id', 'text_fa', 'text_en'];

    public function cooking()
    {
        return $this->belongsTo(FoodCooking::class, 'cooking_id');
    }
}
