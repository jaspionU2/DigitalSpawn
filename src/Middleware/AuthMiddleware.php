<?php

namespace App\Middleware;

use App\Helpers\FlashMessage;
use App\Support\SessionManager;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Laminas\Diactoros\Response\RedirectResponse;
use LogicException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use UnexpectedValueException;

class AuthMiddleware implements MiddlewareInterface
{
    private SessionManager $sessionManager;

    public function __construct()
    {
        $this->sessionManager = SessionManager::getInstance();
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            if (isset($_COOKIE['auth_token'])) {
                JWT::decode(
                    jwt: $_COOKIE['auth_token'],
                    keyOrKeyArray: new Key(keyMaterial: $_ENV['JWT_KEY'], algorithm: 'HS256')
                );

                return $handler->handle($request);
            }

            return new RedirectResponse('/auth/login');
        } catch (SignatureInvalidException $e) {
            FlashMessage::set(
                index: 'flash_message',
                value: [
                    'type' => 'jwtInvalid',
                    'message' => $e->getMessage(),
                    'status' => 401
                ],
                session: $this->sessionManager
            );
            return new RedirectResponse('/auth/login');
        } catch (ExpiredException $e) {
            FlashMessage::set(
                index: 'flash_message',
                value: [
                    'type' => 'jwtExpired',
                    'message' => $e->getMessage(),
                    'status' => 401
                ],
                session: $this->sessionManager
            );
            return new RedirectResponse('/auth/login');
        } catch (LogicException $e) {
            return new RedirectResponse('/auth/login');
        } catch (UnexpectedValueException $e) {
            return new RedirectResponse('/auth/login');
        }
    }
}
