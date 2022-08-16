<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed success
 * @property mixed price
 * @property UserPayment payment
 */
class UserTransaction extends Model
{
    protected $table = 'user_transactions';
    protected $fillable = ['success', 'token', 'reference_id', 'trace_number', 'price'];
    protected $casts = [
        'success' => 'boolean'
    ];

    public function payment()
    {
        return $this->belongsTo(UserPayment::class, 'id', 'transaction_id');
    }
}
