<?php declare(strict_types=1);

use App\Controllers\UserController;
use Laminas\Diactoros\ResponseFactory;
use League\Route\Router;
use League\Route\Strategy\JsonStrategy;

$router = new Router();

$responseFactory = new ResponseFactory;
$jsonStrategy = new JsonStrategy($responseFactory);
$router->setStrategy($jsonStrategy);

$router->map(method: 'GET', path: '/', handler: [UserController::class, 'createUser']);