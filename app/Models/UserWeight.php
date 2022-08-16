<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWeight extends Model
{
    protected $table = 'user_weights';
    protected $fillable = ['user_id', 'weight', 'date'];

    protected $dates = ['date'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
