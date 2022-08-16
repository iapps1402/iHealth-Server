<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDietProgramSupplement extends Model
{
    protected $table = 'user_diet_program_supplements';
    protected $fillable = ['value', 'program_id', 'supplement_id', 'unit_id', 'text', 'unit_text'];

    public function unit()
    {
        return $this->hasOne(FoodUnit::class, 'id', 'unit_id');
    }

    public function supplement()
    {
        return $this->hasOne(Food::class, 'id', 'supplement_id');
    }

    public function program()
    {
        return $this->belongsTo(UserDietProgram::class, 'program_id');
    }
}
