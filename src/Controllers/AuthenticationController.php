<?php

declare(strict_types=1);

namespace App\Controllers;

use Firebase\JWT\JWT;
use App\Helpers\FlashMessage;
use App\Services\UserService;
use App\Services\EmailService;
use App\Support\SessionManager;
use App\Controllers\BaseController;
use DateTime;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\RedirectResponse;

class AuthenticationController extends BaseController
{
    protected UserService $userService;

    protected EmailService $emailService;

    protected SessionManager $sessionManager;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->emailService = new EmailService();
        $this->sessionManager = SessionManager::getInstance();
    }

    public function doLogin(ServerRequestInterface $request): ResponseInterface
    {
        ['email' => $email, 'password' => $password] = $request->getParsedBody();

        $user = $this->userService->getUser(filter: 'email', value: $email);

        if (empty($user) || !password_verify($password, $user->getPasswordHash()) || !$user->isEmailVerified()) {
            FlashMessage::set(
                index: 'flash_message',
                value: ['type' => 'userUnauthorized', 'status' => 401],
                session: $this->sessionManager
            );
            return new RedirectResponse('/auth/login');
        }

        $payload = [
            'iat' => time(),
            'exp' => (new DateTime('+ 7 days'))->getTimestamp(),
            'email' => $email,
            'userId' => $user->getId(),
            'nonce' => uniqid('', true)
        ];

        $jwt = JWT::encode(
            payload: $payload,
            key: $_ENV['JWT_KEY'],
            alg: 'HS256',
            keyId: null,
            head: null
        );

        setcookie(
            name: 'auth_token',
            value: $jwt,
            expires_or_options: [
                'expires' => $payload['exp'],
                'path' => '/',
                'secure' => false,
                'httponly' => true,
                'samesite' => 'Lax'
            ]
        );

        $this->sessionManager->auth_token = $jwt;

        return new RedirectResponse('/');
    }

    public function doLogout(ServerRequestInterface $request): ResponseInterface
    {
        // $this->sessionManager::startSession();

        setcookie(
            name: 'auth_token',
            value: '',
            expires_or_options: [
                'expires' => (new DateTime('- 7 days'))->getTimestamp(),
                'path' => '/',
                'secure' => false,
                'httponly' => true,
                'samesite' => 'Lax'
            ]
        );

        unset($_COOKIE['auth_token']);

        $this->sessionManager->destroySession();

        FlashMessage::set(
            index: 'flash_message',
            value: [
                'type' => 'logoutSuccess',
                'message' => 'logout concluido com sucesso',
                'status' => 200
            ],
            session: $this->sessionManager
        );

        return new RedirectResponse('/auth/login');
    }
}
