<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property ApplicationVersion current
 * @property ApplicationVersion min
 */
class Application extends Model
{
    protected $table = 'applications';
    protected $fillable = ['name_fa', 'name_en', 'current_id', 'min_id'];
    protected $appends = ['current_version_name', 'min_version_name'];


    public function current()
    {
        return $this->belongsTo(ApplicationVersion::class, 'current_id');
    }

    public function min()
    {
        return $this->belongsTo(ApplicationVersion::class, 'min_id');
    }

    public function getCurrentVersionNameAttribute()
    {
        $current = $this->current;

        return $current != null ? $current->name : null;
    }

    public function getMinVersionNameAttribute()
    {
        $min = $this->min;

        return $min != null ? $min->name : null;
    }
}
