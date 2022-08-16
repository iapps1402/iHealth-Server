<?php

namespace App\Http\Controllers\Api\User\Chat2;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index(Request $request)
    {

        $user = $request->user('api');


        if(!$user->is_contact)
            return response()->json([
                'success' => false
            ]);

        $users = User::select('id', 'first_name', 'last_name', 'phone_number')
            ->withCount('unreadMessages')
            ->where('is_contact', 1)
            ->where('id', '<>', $user->id)
            ->orderbyDesc('unread_messages_count')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return response()->json([
            'success' => true,
            'contacts' => $users
        ]);
    }
}
