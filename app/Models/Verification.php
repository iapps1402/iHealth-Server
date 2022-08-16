<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Verification extends Model
{
    use Notifiable;
    protected $table = 'verifications';
    protected $fillable = ['phone_number', 'code', 'email'];

    public function getTypeAttribute() {
            return  empty($this->email) ? 'phone_number' : 'email';

    }
}
