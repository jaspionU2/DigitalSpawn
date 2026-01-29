<?php declare(strict_types=1);

namespace App\Services;

use App\Helpers\VerifyEmail;
use App\Models\EmailTokenModel;
use App\Repository\EmailTokenRepository;
use DateTime;
use Exception;
use Laminas\Diactoros\Response\RedirectResponse;

class EmailService
{
    protected VerifyEmail $emailHelper;
    protected EmailTokenRepository $emailRepository;

    public function __construct()
    {
       $this->emailHelper = new VerifyEmail;
       $this->emailRepository = new EmailTokenRepository;
    }

    public function send(string $toAddress, string $toName): void
    {
        $verificationToken = $this->emailHelper->send($toAddress, $toName);

        $emailTokenModel = EmailTokenModel::create([
            'token' => $verificationToken
        ]);

        $this->emailRepository->saveToken($emailTokenModel);
    }

    public function validateToken(string $token) : bool
    {
       $rowToken = $this->emailRepository->getToken($token);
       
       $datetimeNow = new DateTime(datetime: 'now');
       $datetimeTokenCreated = $rowToken->getTimestamp();
       $interval = $datetimeNow->diff($datetimeTokenCreated, true);
       $intervalInMinutes = ($interval->h * 60) + ($interval->i);

       if ($intervalInMinutes >= 30 || $interval->d > 0) {
            throw new Exception(
                "O token informado não é mais valido", 
                422
            );
       }

       return $rowToken->getToken() === $token;
    }
}