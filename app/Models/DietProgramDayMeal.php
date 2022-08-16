<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DietProgramDayMeal extends Model
{
    protected $table = 'diet_program_day_meals';
    protected $fillable = ['name_fa', 'name_en', 'day_id', 'icon'];

    public function day()
    {
        return $this->belongsTo(DietProgramDay::class, 'day_id');
    }

    public function items()
    {
        return $this->hasMany(DietProgramDayMealItem::class, 'meal_id', 'id');
    }

    public function getDayFaAttribute()
    {
        switch ($this->day) {
            case 'monday':
            default:
                return 'دوشنبه';

            case 'tuesday':
                return 'سه شنبه';

            case 'wednesday':
                return 'چهارشنبه';

            case 'thursday':
                return 'پنج شنبه';

            case 'friday':
                return 'جمعه';

            case 'saturday':
                return 'شنبه';

            case 'sunday':
                return 'یکشنبه';
        }
    }

    public function getDayEnAttribute()
    {
        return ucfirst($this->day);
    }
}
