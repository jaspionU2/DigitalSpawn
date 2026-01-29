<?php

declare(strict_types=1);

namespace App\Helpers;

use function dirname;
use function file_get_contents;

use App\Factories\MailerFactory;
use Dotenv\Dotenv;
use PHPMailer\PHPMailer\Exception;

use function str_replace;

class VerifyEmail
{
    public function send(string $toAddres, string $toName): string
    {
        $dotenv = Dotenv::createMutable(\dirname(__DIR__) . '/../');
        $dotenv->load();

        $token = $this->generateToken();
        $uri = $_ENV['URI_EMAIL_CHECK'] ?? 'http://localhost:8000/register/emailverify?token=';
        $confirmationLink = "{$uri}{$token}";

        $body = file_get_contents(dirname(__DIR__) . '/Views/Auth/emailVerification.html');
        $body = str_replace(
            ['{{name}}', '{{confirmation_link}}'],
            [$toName, $confirmationLink],
            $body,
        );

        $mail = MailerFactory::getInstance();
        $mail->setLanguage('pt_br');
        $mail->msgHTML(true);

        MailerFactory::config(options: [
            'from' => [
                'email' => 'noreply@digitalspawn.com',
                'name' => 'DigitalSpawn',
            ],
            'to' => [
                'email' => $toAddres,
                'name' => $toName,
            ],
            'subject' => 'Confirmation Email',
            'message' => $body
        ]);

        $sent = $mail->send();
        if (!$sent) {
            throw new Exception($mail->ErrorInfo, 1);
        }

        return $token;
    }

    public function generateToken() : string
    {
       return bin2hex(random_bytes(16));
    }
}
