<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = ['user_id', 'message', 'user_to_id', 'read_at'];
    protected $dates = ['read_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
