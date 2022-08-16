<?php

namespace App\Http\Controllers\Api\User\Payment;

use App\APIs\Transaction\Sadad;
use App\Helpers\AndroidDeepLinkBuilder;
use App\Helpers\Constants;
use App\Http\Controllers\Controller;
use App\Models\Coin;
use App\Models\UserPayment;
use App\Models\UserTransaction;
use App\Notifications\UserPaymentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function add(Request $request)
    {
        $validator = Validator::make(Request()->all(), [
            'id' => 'required|numeric|not_in:0'
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(':message')
            ]);

        $coin = Coin::find($request->id);

        if ($coin == null)
            return response()->json([
                'success' => false,
                'message' => Lang::get('messages.error')
            ]);

        $user = Auth::guard('api')->user();

        $token = '';
        try {
            do {
                while (strlen($token) < 191)
                    $token .= uniqid(base64_encode(Str::random(60)));
                $token = substr($token, 0, 190);
            } while (UserTransaction::where('token', $token)->exists());
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => Lang::get('messages.error')
            ]);
        }
        try {
            DB::beginTransaction();
            $transaction = UserTransaction::create([
                'token' => $token,
                'price' => $coin->price,
                'success' => false,
            ]);

            UserPayment::create([
                'user_id' => $user->id,
                'transaction_id' => $transaction->id,
                'coins' => $coin->quantity
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

        return response()->json([
            'success' => true,
            'transaction' => $transaction
        ]);
    }

    public function pay($client, $id, $token)
    {
        if ($client != 'android')
            return abort(404);

        $transaction = UserTransaction::with(['payment'])->where('id', $id)->where('token', $token)->first();

        if ($transaction == null) {
            $deepLink = new AndroidDeepLinkBuilder(AndroidDeepLinkBuilder::$Android_PROTOCOL, 'payment', '', AndroidDeepLinkBuilder::$ANDROID_PACKAGE_NAME, AndroidDeepLinkBuilder::$SCHEMA);
            $deepLink->addParameter('success', 'false');
            $deepLink->addParameter('message', 'خطا رخ داد. فاکتور یافت نشد.');
            $deepLink->addParameter('order_type', 'credit');
            return $deepLink->generate();
        }

        if (Carbon::now()->diffInMinutes($transaction->payment->created_at) > Constants::$MAX_PAYMENT_TIME) {
            $deepLink = new AndroidDeepLinkBuilder(AndroidDeepLinkBuilder::$Android_PROTOCOL, 'payment', '', AndroidDeepLinkBuilder::$ANDROID_PACKAGE_NAME, AndroidDeepLinkBuilder::$SCHEMA);
            $deepLink->addParameter('success', 'false');
            $deepLink->addParameter('message', 'این فاکتور منقضی شده است. لطفا فاکتور جدیدی صادر کنید.');
            $deepLink->addParameter('order_type', 'credit');
            return $deepLink->generate();
        }

        try {
            $gateway = new Sadad($transaction);
            return $gateway->send($transaction->id, $transaction->price, Route('payment_callback', ['client' => $client]), $transaction->payment->user->number);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e->getTraceAsString());
            $deepLink = new AndroidDeepLinkBuilder(AndroidDeepLinkBuilder::$Android_PROTOCOL, 'payment', '', AndroidDeepLinkBuilder::$ANDROID_PACKAGE_NAME, AndroidDeepLinkBuilder::$SCHEMA);
            $deepLink->addParameter('success', 'false');
            $deepLink->addParameter('message', $e->getMessage());
            $deepLink->addParameter('order_type', 'credit');
            return $deepLink->generate();
        }
    }

    public function callback($client)
    {
        if ($client != 'android')
            return abort(404);

        $transaction = UserTransaction::with(['payment'])->find(Request()->OrderId);

        if ($transaction == null) {
            $deepLink = new AndroidDeepLinkBuilder(AndroidDeepLinkBuilder::$Android_PROTOCOL, 'payment', '', AndroidDeepLinkBuilder::$ANDROID_PACKAGE_NAME, AndroidDeepLinkBuilder::$SCHEMA);
            $deepLink->addParameter('success', 'false');
            $deepLink->addParameter('message', 'فاکتور یافت نشد.');
            $deepLink->addParameter('order_type', 'credit');
            return $deepLink->generate();
        }
        if ($transaction->success) {
            $deepLink = new AndroidDeepLinkBuilder(AndroidDeepLinkBuilder::$Android_PROTOCOL, 'payment', '', AndroidDeepLinkBuilder::$ANDROID_PACKAGE_NAME, AndroidDeepLinkBuilder::$SCHEMA);
            $deepLink->addParameter('success', 'false');
            $deepLink->addParameter('message', 'این فاکتور پرداخت شده است.');
            $deepLink->addParameter('reference_id', $transaction->reference_id);
            $deepLink->addParameter('trace_number', $transaction->trace_number);
            $deepLink->addParameter('order_type', 'credit');
            return $deepLink->generate();
        }

        $gateway = new Sadad($transaction);
        try {
            DB::beginTransaction();
            $transaction = $gateway->verify();
            if ($transaction->success) {
                $transaction->payment->user->increment('coins', $transaction->payment->coins);
                Notification::send($transaction->payment->user, new UserPaymentNotification($transaction));
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            $deepLink = new AndroidDeepLinkBuilder(AndroidDeepLinkBuilder::$Android_PROTOCOL, 'payment', '', AndroidDeepLinkBuilder::$ANDROID_PACKAGE_NAME, AndroidDeepLinkBuilder::$SCHEMA);
            $deepLink->addParameter('success', 'false');
            $deepLink->addParameter('message', $exception->getMessage());
            return $deepLink->generate();
        }

        $deepLink = new AndroidDeepLinkBuilder(AndroidDeepLinkBuilder::$Android_PROTOCOL, 'payment', '', AndroidDeepLinkBuilder::$ANDROID_PACKAGE_NAME, AndroidDeepLinkBuilder::$SCHEMA);
        $deepLink->addParameter('success', 'true');
        $deepLink->addParameter('message', 'تراکنش با موفقیت انجام شد.');
        $deepLink->addParameter('trace_number', $transaction->trace_number);
        return $deepLink->generate();
    }
}
