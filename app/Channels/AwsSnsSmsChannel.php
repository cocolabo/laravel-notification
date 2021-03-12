<?php

namespace App\Channels;

use Aws\Sns\SnsClient;
use Illuminate\Notifications\Notification;

class AwsSnsSmsChannel
{
    public function __construct(private SnsClient $sns)
    {
    }

    public function send($notifiable, Notification $notification)
    {
        $phoneNumber = $this->getDestination($notifiable, $notification);

        if ($phoneNumber === null) {
            return;
        }

        if (!method_exists($notification, 'toAwsSnsSms')) {
            return;
        }

        $this->sns->publish([
            'Message'     => $notification->toAwsSnsSms($notifiable),
            'PhoneNumber' => $this->toE164nizeInJapan($phoneNumber),
        ]);
    }

    /**
     * 送信先の電話番号を取得
     *
     * @return string
     */
    private function getDestination($notifiable, Notification $notification): ?string
    {
        // $notifiable(App\Models\User等) に routeNotificationForAwsSnsSmsメソッドがあれば、そのメソッドから電話番号を取得する
        $phoneNumber = $notifiable->routeNotificationFor('aws_sns_sms', $notification);

        if ($phoneNumber !== null && $phoneNumber !== '') {
            return $phoneNumber;
        }

        // カラム名から取得する
        $commonAttributes = ['phone', 'phone_number', 'full_phone'];
        foreach ($commonAttributes as $attribute) {
            if (
                isset($notifiable->{$attribute})
                && $notifiable->{$attribute} !== null
                && $notifiable->{$attribute} !== ''
            ) {
                return $notifiable->{$attribute};
            }
        }

        return null;
    }

    /**
     * AWS SNS で SMS を送信するためには E.164 形式の電話番号が必要
     *
     * @return string
     */
    private function toE164nizeInJapan(string $phoneNumber): string
    {
        return '+81' . substr($phoneNumber, 1);
    }
}
