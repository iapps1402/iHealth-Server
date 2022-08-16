<?php

namespace App\Http\Controllers\Admin\User;

use App\Helpers\HelperMain;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use phplusir\smsir\Smsir;

class UserSingleController extends Controller
{
    public function add(Request $request)
    {
        if ($request->isMethod('GET')) {
            $page = [
                'title' => 'افزودن کاربر',
                'description' => 'ویرایش کاربر',
            ];

            return view('admin.user.single', compact('page'));
        }

        $validator = Validator::make(Request()->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'nullable|regex:/^9\d{9}$/',
            'email' => 'nullable|email',
            'birth_date' => 'required|date',
            'weight' => 'required|numeric|between:10,200',
            'height' => 'required|numeric|between:80,2500',
            'gender' => 'required|in:male,female',
            'activity' => 'required|in:no_physical_activity,sedentary,somehow_active,active,very_active,no_physical_activity',
            'diet_program_expires_at' => 'nullable|date',
            'fat_ratio' => 'required|numeric|not_in:0',
            'protein_ratio' => 'required|numeric|not_in:0',
            'decrease_or_increase_coefficient' => 'required|numeric'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message<br>')
            ]);

        if ($request->phone_number == null && $request->email == null)
            return response()->json([
                'success' => false,
                'message' => 'شماره تماس یا ایمیل را باید وارد کنید.'
            ]);

        if ($request->phone_number != null && User::where('phone_number', $request->phone_number)->exists())
            return response()->json([
                'success' => false,
                'message' => 'این شماره قبلا ثبت شده است.'
            ]);

        if ($request->email != null && User::where('email', $request->email)->exists())
            return response()->json([
                'success' => false,
                'message' => 'این ایمیل قبلا ثبت شده است.'
            ]);

        do {
            $invitationCode = HelperMain::randomString(6);
        } while (User::where('invitation_code', $invitationCode)->exists());

        $user = User::create([
            'diet_program_expires_at' => $request->diet_program_expires_at,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'birth_date' => $request->birth_date,
            'weight' => $request->weight,
            'height' => $request->height,
            'gender' => $request->gender,
            'activity' => $request->activity,
            'invitation_code' => $invitationCode,
            'decrease_or_increase_coefficient' => $request->decrease_or_increase_coefficient,
            'protein_ratio' => $request->protein_ratio,
            'fat_ratio' => $request->fat_ratio
        ]);

        if (!empty($user->phone_number))
            Smsir::ultraFastSend(['phone_number' => $user->first_name . ' ' . $user->last_name], 57098, $user->phone_number);

        return response()->json([
            'success' => true,
            'message' => 'کاربر با موفقیت ثبت شد.',
            'user' => $user,
            'redirect_url' => route('admin_user_edit', ['id' => $user->id])
        ]);
    }

    public function edit($id, Request $request)
    {
        $user = User::findOrFail($id);

        if ($request->isMethod('GET')) {
            $page = [
                'title' => 'ویرایش ' . $user->full_name,
                'description' => 'ویرایش ' . $user->full_name,
            ];

            return view('admin.user.single', compact('user', 'page'));
        }

        $validator = Validator::make(Request()->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'nullable|regex:/^9\d{9}$/',
            'email' => 'nullable|email',
            'birth_date' => 'required|date',
            'weight' => 'required|numeric|between:10,200',
            'height' => 'required|numeric|between:80,2500',
            'gender' => 'required|in:male,female',
            'activity' => 'required|in:no_physical_activity,sedentary,somehow_active,active,very_active,no_physical_activity',
            'diet_program_expires_at' => 'nullable|date',
            'fat_ratio' => 'required|numeric|not_in:0',
            'protein_ratio' => 'required|numeric|not_in:0',
            'decrease_or_increase_coefficient' => 'required|numeric'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message<br>')
            ]);

        if ($request->phone_number == null && $request->email == null)
            return response()->json([
                'success' => false,
                'message' => 'شماره تماس یا ایمیل را باید وارد کنید.'
            ]);

        if ($request->phone_number != null && User::where('phone_number', $request->phone_number)->where('id', '<>', $user->id)->exists())
            return response()->json([
                'success' => false,
                'message' => 'این شماره قبلا ثبت شده است.'
            ]);

        if ($request->email != null && User::where('email', $request->email)->where('id', '<>', $user->id)->exists())
            return response()->json([
                'success' => false,
                'message' => 'این ایمیل قبلا ثبت شده است.'
            ]);

        $user->update([
            'diet_program_expires_at' => $request->diet_program_expires_at,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'birth_date' => $request->birth_date,
            'weight' => $request->weight,
            'height' => $request->height,
            'gender' => $request->gender,
            'activity' => $request->activity,
            'decrease_or_increase_coefficient' => $request->decrease_or_increase_coefficient,
            'protein_ratio' => $request->protein_ratio,
            'fat_ratio' => $request->fat_ratio,
            'calorie' => null
        ]);

        $user = $user->fresh();

        return response()->json([
            'success' => true,
            'message' => 'کاربر با موفقیت ویرایش شد.',
            'user' => $user
        ]);
    }
}
