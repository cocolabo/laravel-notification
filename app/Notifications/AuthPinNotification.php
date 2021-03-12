<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AuthPinNotification extends Notification
{
    use Queueable;

    public function via(mixed $notifiable)
    {
        return ['aws_sns_sms_channel'];
    }

    public function toAwsSnsSms(mixed $notifiable)
    {
        return view('notifications.auth-pin', [
            'pinCode' => $notifiable->pin,
        ])->render();
    }
}
