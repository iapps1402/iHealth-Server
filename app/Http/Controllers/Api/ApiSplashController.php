<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\NotificationChannel;
use Illuminate\Http\Request;

class ApiSplashController extends Controller
{
    public function index()
    {
        $android = Application::where('name_en', 'android')->first();
        $ios = Application::where('name_en', 'ios')->first();
        $channels = NotificationChannel::all();

        return response()->json([
            'success' => true,
            'android' => $android,
            'ios' => $ios,
            'notification_channels' => $channels
        ]);
    }
}
