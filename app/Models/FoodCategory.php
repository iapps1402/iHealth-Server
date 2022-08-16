<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodCategory extends Model
{
    protected $table = 'food_categories';
    protected $fillable = ['name_fa', 'name_en', 'picture_id', 'in_app'];

    public function foods()
    {
        return $this->hasManyThrough(Food::class, FoodCategoryRelation::class, 'category_id', 'id', 'id', 'food_id');
    }

    public function picture()
    {
        return $this->belongsTo(Media::class, 'picture_id');
    }
}
