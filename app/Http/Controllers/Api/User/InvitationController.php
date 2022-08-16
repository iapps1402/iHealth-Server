<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\UserInvitedNotification;
use App\Notifications\UserInvitingNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class InvitationController extends Controller
{
    public function details()
    {
        $user = Auth::guard('api')->user();
        return response()->json([
            'success' => true,
            'invitation_code' => $user->invitation_code,
        ]);
    }

    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invitation_code' => 'required|string'
        ]);
        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message')
            ]);


        $user = Auth::guard('api')->user();

        if ($user->invited_at != null)
            return response()->json([
                'success' => false,
                'message' => Lang::get('messages.already_invited')
            ]);

        if (!$user->can_be_invited)
            return response()->json([
                'success' => false,
                'message' => Lang::get('messages.error')
            ]);

        $invitingUser = User::where('invitation_code', $request->invitation_code)->first();

        if ($invitingUser == null)
            return response()->json([
                'success' => false,
                'message' => Lang::get('messages.invitation_code_not_found')
            ]);

        $invitedCoins = (int) (Setting::where('key', 'invited_coins')->value('value'));
        $invitingCoins = (int) (Setting::where('key', 'inviting_coins')->value('value'));
        try {
            DB::beginTransaction();
            $user->update([
                'invited_by' => $invitingUser->id,
                'invited_at' => now(),
                'coins' => $user->coins + $invitedCoins
            ]);

            $invitingUser->update([
                'coins' => $invitingUser->coins + $invitingCoins
            ]);

            DB::commit();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => Lang::get('messages.error')
            ]);
        }

        Notification::send($user, new UserInvitedNotification($invitedCoins));
        Notification::send($invitingUser, new UserInvitingNotification($invitingCoins));

        return response()->json([
            'success' => true,
            'message' => Lang::get('messages.inviting_code_submitted')
        ]);
    }
}
