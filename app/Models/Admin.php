<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $fillable = ['user_id', 'type'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getTypePersianAttribute()
    {
        switch ($this->type) {
            case 'super':
            default:
                return 'مدیر کل';

            case 'author':
                return 'نویسنده';
        }
    }
}
