<?php

namespace App\Notifications;

use App\APIs\BasicSendPushNotification;
use App\Channels\FcmChannel;
use App\Models\NotificationChannel;
use App\Models\DietProgram;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use stdClass;

class UserDietProgramSentNotification extends Notification
{
    use Queueable;
    protected $program;

    public function __construct($program)
    {
        $this->program = $program;
    }

    public function toFcm($notifiable)
    {
        Lang::setLocale($notifiable->language);

        $data = [
            'title' => Lang::get('messages.system_message'),
            'message' => Lang::get('messages.your_diet_program_has_been_ready'),
            'channel' => NotificationChannel::where('title_en', 'other')->first(),
            'click_action' => 'DietProgramActivity',
            'id' => $this->program->id
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
