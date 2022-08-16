<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table = 'sliders';
    protected $fillable = ['picture_id', 'url'];

    public function picture()
    {
        return $this->hasOne(Media::class, 'id', 'picture_id');
    }
}
