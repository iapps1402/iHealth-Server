<?php

namespace App\Notifications;

use App\APIs\BasicSendPushNotification;
use App\Channels\FcmChannel;
use App\Models\NotificationChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use stdClass;

class UserInvitedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    private $coins;

    public function __construct(int $coins)
    {
        $this->coins = $coins;
    }

    public function toFcm($notifiable)
    {
        Lang::setLocale($notifiable->language);
        $data = [
            'title' => Lang::get('messages.system_message'),
            'message' => str_replace('[coins]', number_format($this->coins), Lang::get('messages.user_invited_notification_message')),
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
