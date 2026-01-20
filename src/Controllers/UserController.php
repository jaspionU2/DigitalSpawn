<?php declare(strict_types=1);

namespace App\Controllers;

use App\Models\UserModel;
use App\Services\UserService;
use Psr\Http\Message\ServerRequestInterface;

class UserController
{
    public function createUser(ServerRequestInterface $request) : void
    {
        ['name' => $name, 'email' => $email, 'password' => $password] = $request->getQueryParams();
        // dump($name, $email, $password);
        $user = new UserModel;
        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword($password);

        $userService = new UserService;
        $userService->createUser($user);
    }
}