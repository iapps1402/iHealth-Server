<?php

namespace App\Http\Controllers;

use App\Helpers\MyEncryption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use ParagonIE\EasyRSA\KeyPair;

class UpdateAppController extends Controller
{

    public function index(Request $request)
    {
        dd(Hash::make('zare1360'));
        $contacts = json_decode('["09160942696"]');
        dd(collect($contacts));
    }
}
