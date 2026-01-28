<?php declare(strict_types=1);

namespace App\Services;

use App\Helpers\VerifyEmail;
use App\Models\EmailTokenModel;
use App\Repository\EmailTokenRepository;

class EmailService
{
    public function send(string $toAddress, string $toName): void
    {
        $verifyEmail = new VerifyEmail();
        $verificationToken = $verifyEmail->send($toAddress, $toName);

        $emailTokenModel = EmailTokenModel::create([
            'token' => $verificationToken
        ]);

        $emailRepository = new EmailTokenRepository();
        $emailRepository->saveToken($emailTokenModel);
    }
}