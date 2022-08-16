<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAppRating extends Model
{
    protected $table = 'user_app_ratings';
    protected $fillable = ['user_id', 'comment', 'rating'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
