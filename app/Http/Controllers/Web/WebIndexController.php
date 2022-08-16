<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WebIndexController extends Controller
{
    public function index()
    {
        return redirect('https://stacklearn.ir');
    }
}
