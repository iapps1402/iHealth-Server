<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed name
 */
class ApplicationVersion extends Model
{
    use SoftDeletes;

    protected $table = 'application_versions';
    protected $fillable = ['number', 'name', 'application_id'];

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    public function changes()
    {
        return $this->hasMany(ApplicationVersionChange::class, 'version_id');
    }
}
