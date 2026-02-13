<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Factories\MailerFactory;

use function bin2hex;
use function dirname;

use Dotenv\Dotenv;

use function file_get_contents;

use PHPMailer\PHPMailer\Exception;

use function random_bytes;
use function str_replace;

/**
 * Classe responsável por enviar e gerar tokens para verificação de e-mail.
 */
class VerifyEmail
{
    /**
     * Envia um e-mail de verificação para o usuário.
     *
     * @param string $toAddres Endereço de e-mail do destinatário.
     * @param string $toName Nome do destinatário.
     * @return string Token gerado para confirmação.
     * @throws Exception Caso o envio do e-mail falhe.
     */
    public function send(string $toAddres, string $toName): string
    {
        $token = $this->generateToken();
        $uri = $_ENV['URI_EMAIL_CHECK'] ?? 'http://localhost:8000/register/emailverify?token=';
        $confirmationLink = "{$uri}{$token}";

        $body = file_get_contents(APP_DIR_PATH . '/Views/Auth/emailVerification.html');
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
            'message' => $body,
        ]);

        $sent = $mail->send();
        if (!$sent) {
            throw new Exception($mail->ErrorInfo, 500);
        }

        return $token;
    }

    /**
     * Gera um token aleatório para verificação de e-mail.
     *
     * @return string Token gerado.
     */
    public function generateToken(): string
    {
        return bin2hex(random_bytes(16));
    }
}
