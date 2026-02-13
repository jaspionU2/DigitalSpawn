<?php

declare(strict_types=1);

namespace App\Services;

use App\Exception\DatabaseException;
use App\Exception\ValidationException;
use App\Helpers\VerifyEmail;
use App\Models\EmailTokenModel;
use App\Repository\TokenRepository;
use DateTime;
use Exception;

class EmailService
{
    protected VerifyEmail $emailHelper;

    protected TokenRepository $tokenRepository;

    public function __construct()
    {
        $this->emailHelper = new VerifyEmail();
        $this->tokenRepository = new TokenRepository();
    }

    public function send(string $toAddress, string $toName): void
    {
        try {
            $verificationToken = $this->emailHelper->send($toAddress, $toName);

            $emailTokenModel = EmailTokenModel::create([
                'token' => $verificationToken,
            ]);

            $this->tokenRepository->saveToken($emailTokenModel);
        } catch (DatabaseException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function validateToken(string $token): EmailTokenModel|null
    {
        try {
            $rowToken = $this->tokenRepository->getToken($token);

            $datetimeNow = new DateTime('now');
            $datetimeTokenCreated = $rowToken->getCreatedAt();
            $intervalInSeconds = $datetimeNow->getTimestamp() - $datetimeTokenCreated->getTimestamp();

            if ($intervalInSeconds > 30 * 60 || $rowToken->isUsed()) {
                throw new ValidationException(
                    'O token informado não é mais valido',
                    422,
                );
            }

            return $rowToken;
        } catch (DatabaseException $e) {
            throw $e;
        } catch (ValidationException $e) {
            throw $e;
        }
    }

    public function updateToken(string $token, array $data): void
    {
        try {
            $this->tokenRepository->updateToken($token, $data);
        } catch (DatabaseException $e) {
            throw $e;
        }
    }
}