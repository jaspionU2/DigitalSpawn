<?php 

namespace App\Strategy;

use App\Factories\TwigFactory;
use Laminas\Diactoros\Response\HtmlResponse;
use League\Route\Http\Exception\NotFoundException;
use League\Route\Strategy\ApplicationStrategy;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CustomApplicationStrategy extends ApplicationStrategy
{
    public function getNotFoundDecorator(NotFoundException $exception): MiddlewareInterface
    {
        return new class implements MiddlewareInterface
        {
            public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
            {
                $twig = TwigFactory::createTwigEnvironment();

                return new HtmlResponse(
                    $twig->render(
                        name: '/Templates/pageNotFound.html.twig'
                    )
                );
            }
        };
    }
}