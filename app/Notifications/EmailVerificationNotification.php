<?php

namespace App\Notifications;

use App\Models\Verification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerificationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail(Verification $code): MailMessage
    {
        $title = 'Verify Your Email Address';
        return (new MailMessage)
            ->subject('Verify Your Email Address')
            ->view('notification.email_confirmation', [
                'title' => $title,
                'code' => $code
            ]);
    }
}
