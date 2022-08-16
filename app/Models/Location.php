<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\Jalalian;

class Location extends Model
{
    protected $fillable = ['user_id', 'latitude', 'longitude'];

    public function getCreatedAtAgoAttribute()
    {
        return Jalalian::fromDateTime($this->created_at)->ago();
    }
}
