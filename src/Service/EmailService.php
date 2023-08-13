<?php

namespace App\Service;

use Exception;
use App\Entity\User;
use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Configuration;
use GuzzleHttp\Client;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailService
{
    private VerifyEmailHelperInterface $verifyEmailHelper;
    private string $sendinblueApiKey;

    public function __construct(VerifyEmailHelperInterface $verifyEmailHelper, string $sendinblueApiKey)
    {
        $this->verifyEmailHelper = $verifyEmailHelper;
        $this->sendinblueApiKey = $sendinblueApiKey;
    }

    public function sendVerificationEmail($destinator, $subject, $htmlContent): void
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $this->sendinblueApiKey);

        $apiInstance = new TransactionalEmailsApi(new Client(['verify' => false]), $config);

        $sendSmtpEmail = new \Brevo\Client\Model\SendSmtpEmail();
        $sendSmtpEmail['to'] = [['email' => $destinator]];
        $sendSmtpEmail['sender'] = ['name' => 'Gift Online', 'email' => 'gift-online@gmail.com'];
        $sendSmtpEmail['htmlContent'] = $htmlContent;
        $sendSmtpEmail['subject'] = $subject;

        try {
            $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
        } catch (Exception $e) {
            // Handle exception as needed
            echo 'Exception when calling TransactionalEmailsApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
        }
    }
}