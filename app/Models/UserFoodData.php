<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed unit_id
 */
class UserFoodData extends Model
{
    protected $table = 'user_food_data';
    protected $fillable = ['unit_id', 'number', 'name'];

    public function unit()
    {
        return $this->belongsTo(FoodUnit::class, 'unit_id');
    }

    public function food()
    {
        return $this->belongsTo(UserFood::class, 'id', 'data_id');
    }
}
