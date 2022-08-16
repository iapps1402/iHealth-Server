<?php

namespace App\Http\Controllers\Api\User\Payment;

use App\Http\Controllers\Controller;
use App\Models\Coin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentDetailsController extends Controller
{
    function index()
    {
        $coins = Coin::orderBy('price')->get();
        $user = Auth::guard('api')->user();
        return response()->json([
            'success' => true,
            'coins' => $coins,
            'inventory' => $user->coins
        ]);
    }


}
