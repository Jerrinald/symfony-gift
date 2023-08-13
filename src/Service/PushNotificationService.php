<?php

namespace App\Service;

use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;

class PushNotificationService
{
    public function __construct(private TexterInterface $texter) {}

    public function sendMessage(string $message, string $phone, string $link): void
    {
        $sms = new SmsMessage(
            $phone,
            "Jaji : " . $message . " https://www.jaji.fr/" . $link,
            '+14345973473'
        );

        $sentMessage = $this->texter->send($sms);
    }

}
