<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;

/**
 * @property mixed protein
 * @property mixed carbs
 * @property mixed fat
 * @property mixed id
 * @property User user
 */
class DietProgram extends Model
{
    protected $table = 'diet_programs';
    protected $fillable = ['user_id', 'note', 'protein', 'carbs', 'fat', 'user_read_at', 'program_period', 'writer_id', 'decrease_or_increase_coefficient'];
    protected $appends = ['calorie'];
    protected $dates = ['user_read_at'];

    public function getCalorieAttribute()
    {
        return $this->protein * 4 + $this->carbs * 4 + $this->fat * 9;
    }

    public function writer()
    {
        return $this->belongsTo(User::class, 'writer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function supplements()
    {
        return $this->hasMany(DietProgramSupplement::class, 'program_id');
    }

    public function days()
    {
        return $this->hasMany(DietProgramDay::class, 'program_id', 'id')->orderBy(DB::raw('field(day, "saturday", "sunday", "monday", "tuesday", "wednesday", "thursday", "friday")'));
    }

    public function getCreatedAtShamsiAttribute()
    {
        return Jalalian::fromCarbon($this->created_at)->format('%A, %d %B %Y');
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

}
