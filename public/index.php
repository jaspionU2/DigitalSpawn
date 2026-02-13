<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . "/../config/bootstrap.php";
require_once __DIR__ . '/../config/router.php';

use App\Support\SessionManager;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

$sessionManager = SessionManager::getInstance([
    'cookie_httponly' => 1,
]);

$request = ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES,
);

$response = $router->dispatch($request);

(new SapiEmitter())->emit($response);
