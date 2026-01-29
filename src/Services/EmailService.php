<?php declare(strict_types=1);

namespace App\Services;

use App\Helpers\VerifyEmail;
use App\Models\EmailTokenModel;
use App\Repository\EmailTokenRepository;
use DateTime;

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

    public function validateToken(string $token) 
    {
       $rowToken = $this->emailRepository->getToken($token);
       
       $datetimeNow = new DateTime(datetime: 'now');
       $datetimeTokenCreated = $rowToken->getTimestamp();
       $interval = $datetimeNow->diff($datetimeTokenCreated, true);

       if ($interval->i >= 30) {
            
       }
      
    }
}