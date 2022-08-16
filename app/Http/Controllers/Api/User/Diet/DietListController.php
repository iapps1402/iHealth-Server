<?php

namespace App\Http\Controllers\Api\User\Diet;

use App\Http\Controllers\Controller;
use App\Models\UserDietProgram;
use Illuminate\Http\Request;

class DietListController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user('api');
        return UserDietProgram::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate();
    }
}
