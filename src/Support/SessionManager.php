<?php

declare(strict_types=1);

namespace App\Support;

use RuntimeException;

class SessionManager
{
    private static ?self $instance = null;

    private function __construct()
    {   
    }

    public static function getInstance(): self
    {
        return self::$instance ??= new self;
    }

    public static function start(array $options = []): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            if (!session_start($options)) {
                throw new RuntimeException('Falha ao iniciar a sessão');
            }
        }
    }
    public function __set(string $name, mixed $value): void
    {
        $_SESSION[$name] = $value;
    }

    public function __get(string $key): mixed
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }

        return null;
    }

    public function hasKey(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    public function unset(string $key): bool
    {
        if ($this->hasKey($key)) {
            unset($_SESSION[$key]);

            return true;
        }

        return false;
    }

    public function sessionStatus(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    public function destroySession(): void
    {
        if ($this->sessionStatus()) {
            session_unset();

            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(
                    name: session_name(),
                    value: '',
                    expires_or_options: time() - 42000,
                    path: $params['path'],
                    domain: $params['domain'],
                    secure: $params['secure'],
                    httponly: $params['httponly'],
                );
            }

            session_destroy();
        }
    }
}
