<?php declare(strict_types=1);

namespace App\Controllers;

use App\Factories\TwigFactory;
use Twig\Extension\DebugExtension;

class Controller
{
    protected function render(string $template, array $context = []) : string
    {
        $twig = TwigFactory::createTwigEnvironment();
        return $twig->render(
            $template, 
            $context
        );
    }
    
}