<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsirLog extends Model
{
    protected $guarded = [];
    protected $table = 'smsir_logs';
    protected $fillable = ['from', 'to', 'message', 'status', 'response'];

    public function sendStatus() {
        if($this->status){
            return '<i class="fa fa-check-circle" aria-hidden="true" style="color: green"></i>';
        }

        return '<i class="fa fa-exclamation-circle" aria-hidden="true" style="color: red"></i>';

    }
}
