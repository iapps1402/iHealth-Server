<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserExerciseProgram extends Model
{
    protected $table = 'user_exercise_programs';
    protected $fillable = ['user_id', 'text'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
