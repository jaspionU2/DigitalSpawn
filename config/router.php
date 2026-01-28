<?php

declare(strict_types=1);

use App\Controllers\AuthenticationController;
use App\Controllers\PageController;
use App\Controllers\UserController;
use Laminas\Diactoros\ResponseFactory;
use League\Route\RouteGroup;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use League\Route\Strategy\JsonStrategy;

$router = new Router();

$responseFactory = new ResponseFactory();
$jsonStrategy = new JsonStrategy($responseFactory);
$router->setStrategy($jsonStrategy);

$router->group(
    '/',
    function (RouteGroup $route) {
        $route->map(method: 'GET', path: '/', handler: [PageController::class, 'homePage']);
        $route->map(method: 'GET', path: '/login', handler: [PageController::class, 'loginPage']);
        $route->map(method: 'GET', path: '/register', handler: [PageController::class, 'registerPage']);
        $route->map(method: 'GET', path: '/register/sendEmail', handler: [PageController::class, 'sendEmailPage']);
    },
)->setStrategy(new ApplicationStrategy());

$router->map(method: 'POST', path: '/register', handler: [AuthenticationController::class, 'doRegister']);

$router->map(method: 'POST', path: '/', handler: [UserController::class, 'createUser']);
