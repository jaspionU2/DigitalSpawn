<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\FlashMessage;
use App\Support\SessionManager;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PageController extends BaseController
{
    protected SessionManager $sessionManager;

    public function __construct()
    {
        $this->sessionManager = SessionManager::getInstance();
    }

    public function homePage(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse(
            $this->render('index.html.twig'),
        );
    }

    public function loginPage(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse(
            $this->render('/Auth/login.html.twig'),
        );
    }

    public function registerPage(ServerRequestInterface $request): ResponseInterface
    {
        $flashMessage = FlashMessage::get(
            index: 'flash_message',
            session: $this->sessionManager,
        );

        if (is_array($flashMessage) && !empty($flashMessage['errors'])) {
            $flashMessage['errors'] = unserialize($flashMessage['errors']);
        } else {
            $flashMessage = [];
        }

        return new HtmlResponse(
            $this->render(
                template: '/Auth/register.html.twig',
                context: compact('flashMessage'),
            ),
        );
    }

    public function sendEmailPage(ServerRequestInterface $request): ResponseInterface
    {
        $userEmail = $this->sessionManager->__get('user')['user_email'];

        return new HtmlResponse(
            $this->render(
                template: '/Auth/sendEmail.html.twig',
                context: compact('userEmail'),
            ),
        );
    }

    public function registerConcludedPage(ServerRequestInterface $request): ResponseInterface
    {
        $this->sessionManager->unset('emailValidationStep');

        $flashMessage = FlashMessage::get(
            index: 'flash_message',
            session: $this->sessionManager
        );

        return new HtmlResponse(
            $this->render(
                template: '/Auth/registerCompleted.html.twig',
                context: compact('flashMessage')
            ),
        );
    }
}
