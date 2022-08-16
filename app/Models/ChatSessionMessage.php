<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatSessionMessage extends Model
{
    protected $fillable = ['user_id', 'message', 'media_id', 'chat_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function chat()
    {
        return $this->belongsTo(ChatSession::class, 'chat_id');
    }
}
