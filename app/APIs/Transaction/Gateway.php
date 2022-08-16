<?php

namespace App\APIs\Transaction;


use Exception;

class Gateway
{

    public static function verifyLocalhost($transaction)
    {

        if ($transaction == null)
            throw new Exception('خطا! فاکتور یافت نشد.');

        $transaction->update([
            'trace_number' => 1,
            'reference_id' => 2,
            'success' => true,
            'message' => 'تراکنش در میزبان محلی با موفقیت انجام شد.'
        ]);
        return $transaction;
    }


}
