<?php

declare(strict_types=1);

namespace App\Factories;

use function dirname;

use Dotenv\Dotenv;

use function is_null;
use function key_exists;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

use function trim;

class MailerFactory
{
    protected static ?PHPMailer $mailer = null;

    public static function getInstance(
        bool $exceptions = false,
    ): PHPMailer {
        if (is_null(self::$mailer)) {
            static::$mailer = new PHPMailer($exceptions);
        }

        return self::$mailer;
    }

    public static function config(
        array $options = [],
        ?string $host = null,
        ?string $username = null,
        ?string $password = null,
        ?string $port = null,
    ): PHPMailer {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__) . '/../');
        $dotenv->load();

        if (is_null(self::$mailer)) {
            self::getInstance();
        }

        // self::$mailer->SMTPDebug = SMTP::DEBUG_SERVER;
        self::$mailer->isSMTP();
        self::$mailer->Host = $host ?? $_ENV['MAILTRAP_HOST'];
        self::$mailer->SMTPAuth = true;
        self::$mailer->Username = $username ?? $_ENV['MAILTRAP_USERNAME'];
        self::$mailer->Password = $password ?? $_ENV['MAILTRAP_PASSWORD'];
        self::$mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        self::$mailer->Port = $port ?? $_ENV['MAILTRAP_PORT'];

        self::$mailer->CharSet = PHPMailer::CHARSET_UTF8;
        self::$mailer->Encoding = 'base64';

        $handlers = [
            'to' => 'setTo',
            'from' => 'setFrom',
            'subject' => 'setSubject',
            'message' => 'setMessage',
        ];

        foreach ($handlers as $key => $method) {
            if (isset($options[$key])) {
                self::$method($options[$key]);
            }
        }

        return self::$mailer;
    }

    protected static function setTo(array $to): void
    {
        if (!key_exists('name', $to) || !key_exists('email', $to)) {
            throw new Exception("As propriedades 'name' e 'email' são obrigatorias em To.");
        }

        self::$mailer->addAddress($to['email'], $to['name']);
    }

    protected static function setFrom(array $from): void
    {
        if (!key_exists('name', $from) || !key_exists('email', $from)) {
            throw new Exception("As propriedades 'name' e 'email' são obrigatorias em From.");
        }

        self::$mailer->setFrom($from['email'], $from['name']);
    }

    protected static function setSubject(string $subject = ''): void
    {
        if (empty(trim($subject))) {
            return;
        }
        self::$mailer->Subject = $subject;
    }

    protected static function setMessage(string $message = ''): void
    {
        if (empty(trim($message))) {
            return;
        }
        self::$mailer->Body = $message;
    }
}
