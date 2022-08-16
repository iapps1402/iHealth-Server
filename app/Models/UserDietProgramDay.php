<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDietProgramDay extends Model
{
    protected $table = 'user_diet_program_days';
    protected $fillable = ['day', 'program_id'];

    public function meals()
    {
        return $this->hasMany(UserDietProgramDayMeal::class, 'day_id', 'id')->orderBy('id');
    }

    public function program()
    {
        return $this->belongsTo(UserDietProgram::class, 'program_id');
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
