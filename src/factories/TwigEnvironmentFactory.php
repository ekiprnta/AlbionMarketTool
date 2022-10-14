<?php

declare(strict_types=1);

namespace MZierdt\Albion\factories;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigEnvironmentFactory
{
    public function __invoke(): Environment
    {
        $loader = new FilesystemLoader(__DIR__ . '/../../templates');
        return new Environment($loader);
    }
}
