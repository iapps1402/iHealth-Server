<?php

namespace App\Http\Controllers\Api\User\SignUpOwn;

use App\Helpers\HelperMain;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserFirebaseToken;
use Google_Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserSignUpGoogleController extends Controller
{
    public function index(Request $request)
    {
        if (Validator::make($request->all(), [
            'id_token' => 'required|string',
            'firebase_token' => 'nullable|string'
        ])->fails())
            return response()->json([
                'success' => 0,
                'message' => 'UnAuthorized!'
            ]);

        $client = new Google_Client(['client_id' => env('GOOGLE_SIGN_IN_CLIENT_ID')]);
        $payload = $client->verifyIdToken($request->id_token);
        if ($payload) {
            if (!empty($payload['email']) && $payload['email_verified']) {
                $user = User::where('email', $payload['email'])->first();

                if ($user == null) {
                    do {
                        $invitationCode = HelperMain::randomString(6);
                    } while (User::where('invitation_code', $invitationCode)->exists());

                    $user = User::create([
                        'email' => $payload['email'],
                        'first_name' => $payload['given_name'],
                        'last_name' => $payload['family_name'],
                        'language' => $request->language,
                        'invitation_code' => $invitationCode
                    ]);
                } else
                    $user->update([
                        'language' => $request->language
                    ]);

                $token = $user->createToken(DB::table('oauth_clients')->where('id', 3)->value('secret'));

                if (!is_null($request->firebase_token))
                    UserFirebaseToken::updateOrCreate([
                        'user_id' => $user->id,
                        'token' => $request->firebase_token
                    ], []);

                return response()->json([
                    'success' => true,
                    'message' => 'با موفقیت وارد شدید',
                    'access_token' => $token->accessToken
                ]);
            }
        }
        return response()->json([
            'success' => 0,
            'message' => 'UnAuthorized!'
        ]);
    }
}
