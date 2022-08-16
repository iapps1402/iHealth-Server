<?php

namespace App\Channels;


use App\APIs\BasicSendPushNotification;
use Illuminate\Notifications\Notification;

class FcmChannel
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
        $fcm = $notification->toFcm($notifiable);
        if ($fcm == null || $fcm->registrationIds == null || !is_object($fcm->registrationIds) || !count($fcm->registrationIds))
            return;

        $push = new BasicSendPushNotification();
        $push->setData($fcm->data);
        $push->setPriority($fcm->priority);

        if (isset($fcm->title))
            $push->setTitle($fcm->title);

        if (isset($fcm->body))
            $push->setBody($fcm->body);

        if (isset($fcm->icon))
            $push->setIcon($fcm->icon);

        $push->sendToRegistrationIds($fcm->registrationIds);
    }
}
