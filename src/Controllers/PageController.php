<?php

declare(strict_types=1);

namespace App\Controllers;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PageController extends BaseController
{
    public function homePage(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse(
            $this->render('index.html.twig'),
        );
    }

    public function loginPage(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse(
            $this->render('Auth/login.html.twig'),
        );
    }

    public function registerPage(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse(
            $this->render('/Auth/register.html.twig'),
        );
    }

    public function sendEmailPage(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse(
            $this->render(
                template: '/Auth/sendEmail.html.twig',
                context: [
                    'user_email' => $_SESSION['user.email'],
                ],
            ),
        );
    }

    public function registerConcludedPage(ServerRequestInterface $request) : ResponseInterface
    {
        return new HtmlResponse(
            $this->render(
                template: '/Auth/registerCompleted.html.twig'
            )
        );
    }
}
