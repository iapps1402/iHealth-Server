<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Lang;

/**
 * @property mixed name_fa
 * @property mixed name_en
 * @property mixed calorie_value
 * @property mixed number
 * @property mixed fiber
 * @property mixed fat
 * @property mixed carbs
 * @property mixed protein
 * @property mixed calorie
 * @property mixed real_protein
 * @property mixed real_carbs
 * @property mixed real_fat
 */
class FoodUnit extends Model
{
    protected $table = 'food_units';
    protected $fillable = ['name_fa', 'name_en', 'carbs', 'fat', 'protein', 'fiber', 'food_id', 'fiber', 'icon', 'default', 'number', 'calorie'];
    protected $appends = ['real_calorie', 'real_protein', 'real_carbs', 'real_fat', 'real_fiber'];

    public function food()
    {
        return $this->belongsTo(Food::class, 'food_id');
    }

    public function materials()
    {
        return $this->hasMany(FoodUnitMaterial::class, 'unit_id', 'id');
    }

    public function getTitleAttribute()
    {
        return Lang::getLocale() == 'fa' ? $this->name_fa : $this->name_en;
    }

    public function getRealFatAttribute()
    {
        return $this->fat / (double)$this->number;
    }

    public function getRealFiberAttribute()
    {
        return $this->fiber / (double)$this->number;
    }

    public function getRealCarbsAttribute()
    {
        return $this->carbs / (double)$this->number;
    }

    public function getRealProteinAttribute()
    {
        return $this->protein / (double)$this->number;
    }

    public function getRealCalorieAttribute()
    {
        if ($this->calorie != null)
            return $this->calorie / (double)$this->number;

        return 4 * $this->real_protein + 4 * $this->real_carbs + 9 * $this->real_fat;
    }
}
