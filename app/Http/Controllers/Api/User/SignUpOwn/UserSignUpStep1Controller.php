<?php

namespace App\Http\Controllers\Api\User\SignUpOwn;

use App\Helpers\HelperSms;
use App\Helpers\HelperString;
use App\Http\Controllers\Controller;
use App\Models\Verification;
use App\Notifications\EmailVerificationNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use phplusir\smsir\Smsir;

class UserSignUpStep1Controller extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'nullable|regex:/^9\d{9}$/',
            'code' => 'required|string|max:5',
            'email' => 'nullable|email',
            'language' => 'required|in:fa,en'
        ]);

        Lang::setLocale($request->language);
        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message')
            ]);

        if (empty($request->phone_number) && empty($request->email))
            return response()->json([
                'success' => false,
                'message' => 'Invalid input.'
            ]);

        $columnName = empty($request->phone_number) ? 'email' : 'phone_number';

        $verification = Verification::where($columnName, $request->post($columnName))->first();

        if ($verification != null) {
            $diff = Carbon::now()->diffInSeconds(Carbon::createFromFormat('Y-m-d H:i:s', $verification->updated_at));
            if ($diff < HelperSms::$maxTimeWaitForNextSms)
                return response()->json([
                    'success' => false,
                    'message' => str_replace('[seconds]', (HelperSms::$maxTimeWaitForNextSms - $diff), Lang::get('messages.wait_for_to_request_again')),
                    'wait_for' => HelperSms::$maxTimeWaitForNextSms - $diff
                ]);
        }

        $verification = Verification::create([
            $columnName => $request->post($columnName),
            'code' => $request->code
        ]);

        if ($columnName == 'phone_number') {
            //dispatch(new SendUltraFastSms(['VerificationCode' => $rand], 44032, $request->phone_number));

         //   Smsir::ultraFastSend(['VerificationCode' => $request->code], 44032, $request->phone_number);
            return response()->json([
                'success' => true,
                'message' => Lang::get('messages.sms_has_been_sent'),
                'wait_for' => HelperSms::$maxTimeWaitForNextSms
            ]);
        } else {
            $verification->notify(new EmailVerificationNotification());
            return response()->json([
                'success' => true,
                'message' => Lang::get('messages.email_has_been_sent'),
                'wait_for' => HelperSms::$maxTimeWaitForNextSms
            ]);
        }
    }
}
