<?php

declare(strict_types=1);

namespace App\Services;

use App\Helpers\VerifyEmail;
use App\Models\EmailTokenModel;
use App\Repository\EmailTokenRepository;
use DateTime;
use DateTimeZone;
use Exception;

class EmailService
{
    protected VerifyEmail $emailHelper;

    protected EmailTokenRepository $emailRepository;

    public function __construct()
    {
        $this->emailHelper = new VerifyEmail();
        $this->emailRepository = new EmailTokenRepository();
    }

    public function send(string $toAddress, string $toName): void
    {
        $verificationToken = $this->emailHelper->send($toAddress, $toName);

        $emailTokenModel = EmailTokenModel::create([
            'token' => $verificationToken,
        ]);

        $this->emailRepository->saveToken($emailTokenModel);
    }

    public function validateToken(string $token): EmailTokenModel|null
    {
        $rowToken = $this->emailRepository->getToken($token);

        $datetimeNow = new DateTime('now', new DateTimeZone('America/Bahia'));
        $datetimeTokenCreated = new DateTime($rowToken->getTimestamp()->format('Y-m-d H:i:s'), new DateTimeZone('America/Sao_Paulo'));
        $interval = $datetimeNow->diff($datetimeTokenCreated, true);
        $intervalInMinutes = ($interval->h * 60) + ($interval->i);
    
        if ($intervalInMinutes >= 30 || $interval->d > 0) {
            throw new Exception(
                'O token informado não é mais valido',
                422,
            );
        }

        return $rowToken;
    }
}
