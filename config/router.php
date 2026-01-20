<?php declare(strict_types=1);

use App\Controllers\UserController;
use League\Route\Router;

$router = new Router();

$router->map(method: 'GET', path: '/', handler: [UserController::class, 'createUser']);