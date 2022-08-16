<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use phplusir\smsir\Smsir;

class UltraFastSmsChannel
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
        $sms = $notification->toFastSms($notifiable);
        if (empty($sms->template_id) || !isset($sms->parameters) || !is_array($sms->parameters))
            return;

        $number = $notifiable->number;

        if (!Str::startsWith($number, '0'))
            $number = '0' . $number;

        Smsir::ultraFastSend($sms->parameters, $sms->template_id, $number);
    }
}
