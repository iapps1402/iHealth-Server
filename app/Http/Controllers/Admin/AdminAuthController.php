<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{

    public function login(Request $request)
    {
        $user = $request->user();
        if ($user != null && $user->is_admin)
            return redirect(Route('admin_dashboard'));

        if (Request()->isMethod('GET')) {
            $page = [
                'title' => 'ورود به پنل ادمین',
                'description' => 'ورود به پنل ادمین'
            ];

            return view('admin.login', compact('page'));
        }

        $validator = Validator::make(Request()->all(), [
            'username' => 'required',
            'password' => 'required|string'
        ]);

        if ($validator->fails())
            return redirect()->back()->withErrors($validator->errors())->withInput(Request()->all());

        if (!Auth::guard('web')->attempt([
                'email' => $request->username,
                'password' => $request->password
            ], $request->has('remember') && $request->remember == 'on')
            || !Auth::guard('web')->attempt([
                'phone_number' => $request->username,
                'password' => $request->password
            ], $request->has('remember') && $request->remember == 'on')
        )
            return redirect()->back()->withErrors([
                0 => 'ایمیل یا رمز عبور اشتباه است.'
            ]);

        $user = Auth::guard('web')->user();

        if ($user == null || !$user->is_admin)
            return redirect()->back()->withErrors([
                0 => 'ایمیل یا رمز عبور اشتباه است.'
            ]);

        return redirect(Route('admin_dashboard'));
    }

    public function logout()
    {
        if (Auth::guard('web')->check())
            Auth::guard('web')->logout();

        return redirect()->route('admin_login');
    }

}
