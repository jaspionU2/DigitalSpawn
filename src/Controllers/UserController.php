<?php declare(strict_types=1);

namespace App\Controllers;

use App\Models\UserModel;
use App\Services\UserService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\JsonResponse;

class UserController
{
public function createUser(ServerRequestInterface $request) : ResponseInterface
    {
        ['name' => $name, 'email' => $email, 'password' => $password] = $request->getQueryParams();
        $user = UserModel::create([
            'name' => $name,
            'email' => $email,
            'password_hash' => $password
        ]);

        dump($user);

        $userService = new UserService;
        $userService->createUser($user);

        return new JsonResponse(['status' => 'ok', 'user' => $user->getId()], encodingOptions: JSON_PRETTY_PRINT);
    }
}