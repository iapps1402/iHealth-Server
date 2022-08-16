<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Lang;

/**
 * @property mixed category_id
 * @property mixed id
 */
class Food extends Model
{
    protected $table = 'foods';
    protected $fillable = ['name_fa', 'name_en', 'picture_id', 'cooking_id', 'description_fa', 'description_en', 'barcode'];
    protected $appends = ['show_cooking'];

    public function units()
    {
        return $this->hasMany(FoodUnit::class, 'food_id', 'id');
    }

    public function getDefaultUnitAttribute()
    {
        $unit = $this->units()->where('default', 1)->first();
        if ($unit == null)
            return null;

        return $unit;
    }

    public function suggestions()
    {
        return $this->hasMany(FoodSuggestion::class, 'food_id');
    }

    public function picture()
    {
        return $this->hasOne(Media::class, 'id', 'picture_id');
    }

    public function categoryRelations()
    {
        return $this->hasMany(FoodCategoryRelation::class, 'food_id');
    }

    public function categories()
    {
        return $this->hasManyThrough(FoodCategory::class, FoodCategoryRelation::class, 'food_id', 'id', 'id', 'category_id');
    }

    public function getTagFaAttribute()
    {
        return '#' . str_replace(' ', '_', $this->name_fa);
    }

    public function getSimilaritiesAttribute()
    {
        $categories = $this->categories()->pluck('food_categories.id');
        return Food::with(['picture.thumbnail'])->where('id', '<>', $this->id)
            ->whereHas('categoryRelations', function ($q) use ($categories) {
                $q->whereIn('category_id', $categories);
            });

    }

    public function getTitleAttribute()
    {
        return Lang::getLocale() == 'fa' ? $this->name_fa : $this->name_en;
    }

    public function cooking()
    {
        return $this->hasOne(FoodCooking::class, 'id', 'cooking_id');
    }

    public function userFoods()
    {
        return $this->hasMany(UserFood::class, 'food_id');
    }

    public function getShowCookingAttribute()
    {
        $cooking = $this->cooking()->withCount(['instructions', 'ingredients'])->first();

        return $cooking->instructions_count > 0 || $cooking->ingredients_count > 0;
    }
}
