<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class Media extends Model
{
    protected $table = 'medias';
    protected $fillable = ['mime_type', 'size', 'thumbnail_id', 'path', 'filename', 'uploaded'];
    protected $appends = ['absolute_path'];
    protected $casts = [
      'uploaded' => 'boolean'
    ];

    public function thumbnail()
    {
        return $this->belongsTo(Media::class, 'thumbnail_id');
    }

    public function getAbsolutePathAttribute()
    {
        //return $this->getAbsoluteAttribute();

        if ($this->path != null)
            return URL::to(Storage::url($this->path));
//        return 'https://shahrinoapp.ir' . Storage::url($this->path);

        return null;
    }

    public function getAbsoluteAttribute()
    {
        if ($this->path != null)
            return URL::to(Storage::url($this->path));

        return URL::to(Storage::url('images/no_image.png'));
    }

    public function getRelativeAttribute()
    {
        if ($this->path != null) {
            return Storage::url($this->path);
        }

        return Storage::url('images/no_image.png');
    }
}
