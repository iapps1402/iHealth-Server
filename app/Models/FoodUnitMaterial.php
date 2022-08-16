<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodUnitMaterial extends Model
{
    protected $table = 'food_unit_materials';
    protected $fillable = ['unit_id', 'name_fa', 'name_en', 'value'];

    public function food()
    {
        return $this->belongsTo(FoodUnit::class, 'unit_id');
    }
}
