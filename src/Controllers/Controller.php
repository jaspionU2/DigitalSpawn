<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Factories\TwigFactory;

class Controller
{
    protected function render(string $template, array $context = []): string
    {
        $twig = TwigFactory::createTwigEnvironment();

        return $twig->render(
            $template,
            $context,
        );
    }
}
