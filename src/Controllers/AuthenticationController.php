<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\UserModel;
use App\Schemas\UserSchema;
use App\Services\EmailService;
use App\Services\UserService;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;

use function password_hash;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

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
            
            return new RedirectResponse('/register/sendEmail');
        }

        return new RedirectResponse('/register');
    }

    public function verifyEmailToken(ServerRequestInterface $request)
    {
        $token = $request->getQueryParams();
    }
}
