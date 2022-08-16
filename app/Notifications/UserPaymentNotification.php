<?php

namespace App\Notifications;

use App\APIs\BasicSendPushNotification;
use App\Channels\FcmChannel;
use App\Models\NotificationChannel;
use App\Models\UserTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use stdClass;

class UserPaymentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $transaction;

    /**
     * Create a new notification instance.
     *
     * @param UserTransaction $transaction
     */
    public function __construct(UserTransaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function toFcm($notifiable)
    {
        Lang::setLocale($notifiable->language);
        $data = [
            'title' => Lang::get('messages.system_message'),
            'message' => Lang::get('messages.buy_coins_notification_message'),
            'coins' => $this->transaction->payment->user->coins,
            'channel' => NotificationChannel::where('title_en', 'other')->first(),
            'click_action' => 'MainActivity'
        ];

        $fcm = new stdClass();
        $fcm->data = $data;
        $fcm->priority = BasicSendPushNotification::$PRIORITY_HIGH;
        $fcm->registrationIds = $notifiable->firebase()->take(6)->get()->pluck('token');
        return $fcm;
    }

    public function via($notifiable)
    {
        return [FcmChannel::class];
    }
}
