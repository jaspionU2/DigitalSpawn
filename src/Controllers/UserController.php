<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Exception\DatabaseException;
use App\Exception\ValidationException;
use App\Helpers\FlashMessage;

use App\Models\UserModel;
use App\Schemas\UserSchema;
use App\Services\EmailService;
use App\Services\UserService;
use App\Support\SessionManager;
use Exception;
use Laminas\Diactoros\Response\RedirectResponse;

use function password_hash;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


use function serialize;

class UserController extends BaseController
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

    public function doRegister(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();

        $userSchema = new UserSchema();
        $errors = $userSchema->validate($data);
        if (empty($errors)) {
            $user = UserModel::create([
                'name' => $data['name'],
                'lastname' => $data['lastname'],
                'email' => $data['email'],
                'password' => password_hash($data['password'], PASSWORD_BCRYPT),
                'telephone' => $data['telephone'],
            ]);
            try {
                $this->userService->createUser($user);
                $this->emailService->send($data['email'], $data['name']);
                $this->sessionManager->__set(
                    name: 'user',
                    value: [
                        'user_email' => $data['email'],
                        'user_id' => $user->getId(),
                    ],
                );
                FlashMessage::set(
                    index: 'flash_message',
                    value: ['type' => 'success', 'message' => 'Usuario cadastrado com sucesso'],
                    session: $this->sessionManager,
                );

                $this->sessionManager->__set('registerStep', true);

                return new RedirectResponse('auth/register/sendEmail');
            } catch (DatabaseException $e) {
                FlashMessage::set(
                    index: 'flash_message',
                    value: ['type' => 'error', 'message' => $e->getMessage()],
                    session: $this->sessionManager,
                );

                return new RedirectResponse('auth/register');
            } catch (Exception $e) {
                FlashMessage::set(
                    index: 'flash_message',
                    value: ['type' => 'error', 'message' => $e->getMessage()],
                    session: $this->sessionManager,
                );

                return new RedirectResponse('auth/register');
            }
        }
        
        FlashMessage::set(
            index: 'flash_message',
            value: ['type' => 'validateError', 'errors' => serialize($errors)],
            session: $this->sessionManager,
        );
        return new RedirectResponse('auth/register');
    }

    public function verifyEmailToken(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getQueryParams();

        try {
            $validatedToken = $this->emailService->validateToken($data['token']);
            
            $userId = FlashMessage::get('user', $this->sessionManager)['user_id'];
            $this->userService->updateUser(
                id: $userId,
                data: [
                    'emailVerified' => true,
                ],
            );
            $this->emailService->updateToken(
                token: $validatedToken->getToken(),
                data: [
                    'isUsed' => true
                ]
            );

            FlashMessage::set(
                index: 'flash_message',
                value: ['type' => 'success'],
                session: $this->sessionManager
            );

            $this->sessionManager->__set('emailValidationStep', true);
            $this->sessionManager->unset('registerStep');

            return new RedirectResponse('auth/register/concluded');
        
        } catch (ValidationException $e) {
            FlashMessage::set(
                index: 'flash_message',
                value: ['type' => 'validateError', 'message' => $e->getMessage()],
                session: $this->sessionManager
            );
            return new RedirectResponse('auth/register/concluded');
        } catch (Exception $e) {
            FlashMessage::set(
                index: 'flash_message',
                value: ['type' => 'validateError', 'message' => $e->getMessage()],
                session: $this->sessionManager
            );
            return new RedirectResponse('auth/register/concluded');
        }
    }
}