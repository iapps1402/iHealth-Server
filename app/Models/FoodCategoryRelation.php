<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodCategoryRelation extends Model
{
    protected $table = 'food_category_relations';
    protected $fillable = ['category_id', 'food_id'];

    public function food()
    {
        return $this->belongsTo(Food::class, 'food_id');
    }

    public function category()
    {
        return $this->belongsTo(FoodCategory::class, 'category_id');
    }
}
