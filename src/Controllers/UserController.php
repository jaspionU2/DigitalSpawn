<?php declare(strict_types=1);

namespace App\Controllers;

use App\Models\UserModel;
use App\Services\UserService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\JsonResponse;

class UserController extends Controller
{
public function createUser(ServerRequestInterface $request) : ResponseInterface
    {
        ['name' => $name, 'email' => $email, 'password' => $password] = $request->getQueryParams();
        $user = UserModel::create([
            'name' => $name,
            'email' => $email,
            'password_hash' => $password
        ]);

        $userService = new UserService;
        $userService->createUser($user);
        

        return new JsonResponse(['status' => 'ok', 'user' => $user->toArray()], encodingOptions: JSON_PRETTY_PRINT);
    }
}