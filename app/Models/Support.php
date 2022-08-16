<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    protected $table = 'supports';
    protected $fillable = ['photo_id', 'full_name', 'telegram_id', 'whatsapp_number'];

    public function photo()
    {
        return $this->belongsTo(Media::class, 'photo_id');
    }
}
