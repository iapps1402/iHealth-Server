<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodSuggestion extends Model
{
    protected $table = 'food_suggestions';
    protected $fillable = ['food_id', 'unit_id'];

    public function food()
    {
        return $this->hasOne(Food::class, 'id', 'food_id');
    }

    public function unit()
    {
        return $this->hasOne(FoodUnit::class, 'id', 'unit_id');
    }
}
