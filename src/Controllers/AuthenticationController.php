<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\UserModel;
use App\Schemas\UserSchema;
use App\Services\EmailService;
use App\Services\UserService;
use Laminas\Diactoros\Response\RedirectResponse;

use function password_hash;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthenticationController extends Controller
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

            return new RedirectResponse('/register/sendEmail', 302);
        }

        return new RedirectResponse('/register');
    }
}
