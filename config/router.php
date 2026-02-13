<?php

declare(strict_types=1);

use App\Controllers\AuthenticationController;
use App\Controllers\PageController;
use App\Controllers\UserController;
use App\Middleware\AuthMiddleware;
use App\Strategy\CustomApplicationStrategy;
use App\Support\SessionManager;
use Laminas\Diactoros\Response\RedirectResponse;
use League\Route\RouteGroup;
use League\Route\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

$router = new Router();
$router->setStrategy(new CustomApplicationStrategy);

/**
 * ROTAS PÚBLICAS
 */
$router->group(
    '/auth',
    function (RouteGroup $route) {
        $route->map(method: 'GET', path: '/register', handler: [PageController::class, 'registerPage']);
        $route->map(method: 'POST', path: '/register', handler: [UserController::class, 'doRegister']);
        $route->map(method: 'GET', path: '/login', handler: [PageController::class, 'loginPage']);
        $route->map(method: 'POST', path: '/login', handler: [AuthenticationController::class, 'doLogin']);
        $route->map(method: 'POST', path: '/register/emailVerify', handler: [UserController::class, 'verifyEmailToken']);
    }
);

$router->group(
    '/auth',
    function (RouteGroup $route) {
        $route->map(method: 'GET', path: '/register/sendEmail', handler: [PageController::class, 'sendEmailPage'])->middleware(
            new class implements MiddlewareInterface 
            {
                public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
                {
                    $sessionManager = SessionManager::getInstance();

                    if ($sessionManager->hasKey('registerStep') && $sessionManager->__get('registerStep')) {
                        return $handler->handle($request);
                    }

                    return new RedirectResponse('/auth/register');
                }
            }
        );
        $route->map(method: 'GET', path: '/register/concluded', handler: [PageController::class, 'registerConcludedPage'])->middleware(
            new class implements MiddlewareInterface
            {
                public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
                {
                    $sessionManager = SessionManager::getInstance();

                    if ($sessionManager->hasKey('emailValidationStep') && $sessionManager->__get('emailValidationStep')) {
                        return $handler->handle($request);
                    }

                    return new RedirectResponse('/auth/register');
                }
            }
        ); 
    }
);

$router->group(
    '/',
    function (RouteGroup $route) {
        $route->map(method: 'GET', path: '/', handler: [PageController::class, 'homePage']);
        $route->map(method: 'POST', path: '/logout', handler: [AuthenticationController::class, 'doLogout']);
    }
)->middleware(new AuthMiddleware);

// $router->group(
//     '/',
//     function (RouteGroup $route) {
//         $route->map(method: 'GET', path: '/', handler: [PageController::class, 'homePage']);
//         // $route->map(method: 'GET', path: '/login', handler: [PageController::class, 'loginPage']);
//         // $route->map(method: 'GET', path: '/register', handler: [PageController::class, 'registerPage']);
//         $route->map(method: 'GET', path: '/register/sendEmail', handler: [PageController::class, 'sendEmailPage']);
//         $route->map(method: 'GET', path: '/register/concluded', handler: [PageController::class, 'registerConcludedPage']);
//     },
// );

// $router->map(method: 'POST', path: '/register', handler: [UserController::class, 'doRegister']);
// $router->map(method: 'POST', path: '/register/emailVerify', handler: [UserController::class, 'verifyEmailToken']);

// $router->map(method: 'POST', path: '/login', handler: [AuthenticationController::class, 'doLogin']);
// $router->map(method: 'POST', path: '/logout', handler: [AuthenticationController::class, 'doLogout']);