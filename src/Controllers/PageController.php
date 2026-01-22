<?php declare(strict_types=1);

namespace App\Controllers;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PageController extends Controller
{
    public function index(ServerRequestInterface $request) : ResponseInterface
    {
        return new HtmlResponse(
            $this->render('index.html.twig')
        );
    }

    public function login(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse(
            $this->render('login.html.twig')
        );
    }
}