<?php

namespace App\Channels;
use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use phplusir\smsir\Smsir;

class SmsChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @return void
     */

    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toSms($notifiable);
        if ($message == null)
            return;

        $phoneNumber = $notifiable->phone_number;

        if (!Str::startsWith($phoneNumber, '0'))
            $phoneNumber = '0' . $phoneNumber;

       // Smsir::send($message . "\n" . Main::$APP_NAME_FA, $number);
    }
}
