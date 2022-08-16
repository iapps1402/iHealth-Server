<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\Jalalian;

class Message extends Model
{
    protected $table = 'messages';
    protected $fillable = ['text', 'number', 'admin_read_at', 'user_id'];
    protected $dates = ['admin_read_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getCreatedAtAgoAttribute()
    {
        return Jalalian::fromDateTime($this->created_at)->ago();
    }
}
