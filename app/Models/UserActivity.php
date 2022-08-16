<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    protected $table = 'user_activities';
    protected $fillable = ['relation_id', 'name_fa', 'name_en', 'calorie_ratio', 'minutes'];

    public function relation()
    {
        return $this->hasOne(UserDateRelation::class, 'id', 'relation_id');
    }
}
