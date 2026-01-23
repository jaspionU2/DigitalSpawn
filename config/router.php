<?php

declare(strict_types=1);

use App\Controllers\PageController;
use App\Controllers\UserController;
use Laminas\Diactoros\ResponseFactory;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use League\Route\Strategy\JsonStrategy;

$router = new Router();

$responseFactory = new ResponseFactory;
$jsonStrategy = new JsonStrategy($responseFactory);
$router->setStrategy($jsonStrategy);

$router->group('/admin', function (\League\Route\RouteGroup $route) 
{
    $route->map(method: 'GET', path: '/', handler: [PageController::class, 'homePage'])->setStrategy(new ApplicationStrategy());
    $route->map(method: 'GET', path: '/login', handler: [PageController::class, 'loginPage'])->setStrategy(new ApplicationStrategy());
    $route->map(method: 'GET', path: '/Register', handler: [PageController::class, 'registerPage'])->setStrategy(new ApplicationStrategy());
}
)->setStrategy(new ApplicationStrategy);


$router->map(method: 'POST', path: '/', handler: [UserController::class, 'createUser']);

$router->map(method: 'POST', path: '/register', handler: [AuthenticationController::class, 'doRegister'])->setStrategy(new ApplicationStrategy());
