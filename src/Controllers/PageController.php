<?php

declare(strict_types=1);

namespace App\Controllers;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PageController extends Controller
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
            $this->render('/Auth/sendEmail.html.twig'),
        );
    }
}
