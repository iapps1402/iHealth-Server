<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationChannel extends Model
{
    protected $table = 'notification_channels';
    protected $fillable = ['title_fa', 'title_en', 'description_fa', 'description_en', 'importance'];
}
