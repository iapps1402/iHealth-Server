<?php

namespace App\Http\Controllers\Api\User\Main;

use App\Http\Controllers\Controller;
use App\Models\ApplicationVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class MainVersionController extends Controller
{
    public function index($os, $versionName, Request $request)
    {
        if ($os != 'android' && $os != 'ios')
            return response()->json([
                'success' => false,
                'message' => Lang::get('messages.not_found')
            ]);

        $version = ApplicationVersion::whereHas('application', function ($q) use ($os) {
            $q->where('name_en', $os);
        })->where('name', $versionName)
            ->first();

        if ($version == null)
            return response()->json([
                'success' => false,
                'message' => Lang::get('messages.not_found')
            ]);

        $latestVersions = ApplicationVersion::with(['changes'])->where('id', '<=', $version->number)
            ->where('application_id', $version->application_id)
            ->orderByDesc('number')
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'versions' => $latestVersions
        ]);
    }
}
