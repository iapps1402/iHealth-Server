<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationVersionChange extends Model
{
    protected $table = 'application_version_changes';
    protected $fillable = ['version_id', 'text_fa', 'text_en'];

    public function version()
    {
        return $this->belongsTo(ApplicationVersion::class, 'version_id');
    }
}
