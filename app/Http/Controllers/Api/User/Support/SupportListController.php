<?php

namespace App\Http\Controllers\Api\User\Support;

use App\Http\Controllers\Controller;
use App\Models\Support;
use Illuminate\Http\Request;

class SupportListController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => Support::with(['photo.thumbnail'])->orderByDesc('created_at')->get()
        ]);
    }
}
