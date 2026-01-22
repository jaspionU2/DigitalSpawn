<?php declare(strict_types=1);

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


$router->group('/', function (\League\Route\RouteGroup $route) 
{
    $route->map(method: 'GET', path: '/', handler: [PageController::class, 'index']);
    $route->map(method: 'GET', path: '/login', handler: [PageController::class, 'login']);
}
)->setStrategy(new ApplicationStrategy());

$router->map(method: 'POST', path: '/', handler: [UserController::class, 'createUser']);
