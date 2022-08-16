<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property UserTransaction transaction
 * @property User user
 */
class UserPayment extends Model
{
    protected $table = 'user_payments';
    protected $fillable = ['coins', 'user_id', 'transaction_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transaction()
    {
        return $this->hasOne(UserTransaction::class, 'id', 'transaction_id');
    }

    public function getHasSucceedAttribute()
    {
        return $this->transaction->success;
    }
}
