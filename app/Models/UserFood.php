<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property UserFoodData data
 * @property mixed food_id
 */
class UserFood extends Model
{
    protected $table = 'user_foods';
    protected $fillable = ['name_fa', 'name_en', 'carbs', 'fat', 'protein', 'fiber', 'food_id', 'relation_id', 'meal', 'description_fa', 'description_en', 'data_id'];
    protected $appends = ['calorie'];

    public function food()
    {
        return $this->hasOne(Food::class, 'id', 'food_id');
    }

    public function getCalorieAttribute()
    {
        return ($this->protein * 4) + ($this->fat * 9) + ($this->carbs * 4);
    }

    public function relation()
    {
        return $this->hasOne(UserDateRelation::class, 'id', 'relation_id');
    }

    public function data()
    {
        return $this->belongsTo(UserFoodData::class, 'data_id');
    }

    public function getCanBeEditedAttribute()
    {
        $data = $this->data;

        return $data != null && (($data->unit_id != null && $data->number != null)
            || ($this->food_id == null && $data->unit_id == null && $data->number == null && $data->name != null));
    }
}
