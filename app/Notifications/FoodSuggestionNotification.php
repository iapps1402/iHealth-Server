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

class FoodSuggestionNotification extends Notification implements ShouldQueue
{
    use Queueable;
    private $coins;

    public function toFcm($notifiable)
    {
        Lang::setLocale($notifiable->language);
        $data = [
            'title' => Lang::get('messages.system_message'),
            'message' => Lang::get('messages.food_suggestion_message'),
            'channel' => NotificationChannel::where('title_en', 'other')->first(),
            'click_action' => 'AnalyzeDishActivity'
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
