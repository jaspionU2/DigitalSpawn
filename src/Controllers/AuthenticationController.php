<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\EmailTokenModel;
use App\Models\UserModel;
use App\Schemas\UserSchema;
use App\Services\EmailService;
use App\Services\UserService;
use Exception;
use Laminas\Diactoros\Response\RedirectResponse;

use function password_hash;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthenticationController extends BaseController
{
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

            $userService = new UserService();
            $userService->createUser($user);

            $emailService = new EmailService();
            $emailService->send($data['email'], $data['name']);

            $_SESSION['user.email'] = $data['email'];
            $_SESSION['user.id'] = $user->getId();

            return new RedirectResponse('/register/sendEmail');
        }

        return new RedirectResponse('/register');
    }

    public function verifyEmailToken(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getQueryParams();
        try {
            $emailService = new EmailService();
            $validatedToken = $emailService->validateToken($data['token']);
            if ($validatedToken instanceof EmailTokenModel) {
                $userService = new UserService();
                $userService->updateUser(
                    id: $_SESSION['user.id'],
                    data: [
                        'emailVerified' => true,
                    ],
                );
                
                return new RedirectResponse('/register/concluded', 302);
            } else {
                throw new Exception(
                    message: 'Token não encontrado',
                    code: 400,
                );
            }
        } catch (Exception $e) {
            return new RedirectResponse('/register/concluded', 302);
        }
    }
}
