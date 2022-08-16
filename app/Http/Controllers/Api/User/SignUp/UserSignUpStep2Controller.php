<?php

namespace App\Http\Controllers\Api\User\SignUp;

use App\Helpers\HelperMain;
use App\Helpers\HelperSms;
use App\Http\Controllers\Controller;
use App\Models\Verification;
use App\Models\User;
use App\Models\UserFirebaseToken;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class UserSignUpStep2Controller extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'nullable|regex:/^9\d{9}$/',
            'email' => 'nullable|email',
            'code' => 'required|string',
            'firebase_token' => 'nullable|string',
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

        $verification = Verification::where($columnName, $request->post($columnName))
            ->where('code', $request->code)
            ->first();

        if ($verification == null)
            return response()->json([
                'success' => false,
                'message' => Lang::get('messages.verification_code_incorrect')
            ]);

        if ($verification->code != $request->code)
            return response()->json([
                'success' => false,
                'message' => Lang::get('messages.verification_code_incorrect')
            ]);

        if (Carbon::parse($verification->updated_at)->addMinutes(HelperSms::$maxTimeSMSValidate) < Carbon::now()) {
            $verification->delete();
            return response()->json([
                'success' => false,
                'message' => Lang::get('messages.try_another_time')
            ]);
        }

        $user = User::where($verification->type, $request->post($verification->type))->first();

        if ($user == null) {
            $invitationCode = null;
            do {
                $invitationCode = HelperMain::randomString(6);
            } while (User::where('invitation_code', $invitationCode)->exists());

            $user = User::create([
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'language' => $request->language,
                'invitation_code' => $invitationCode
            ]);
        } else
            $user->update([
                'language' => $request->language
            ]);

        $verification->delete();

        if (!is_null($request->firebase_token))
            UserFirebaseToken::updateOrCreate([
                'user_id' => $user->id,
                'token' => $request->firebase_token
            ], []);

        $token = $user->createToken(DB::table('oauth_clients')->where('name', 'Android')->value('secret'));

        return response()->json([
            'success' => true,
            'message' => Lang::get('messages.logged_in_successfully'),
            'access_token' => $token->accessToken
        ]);
    }
}
