<?php

declare(strict_types=1);

namespace MZierdt\Albion\factories;

use Twig\Environment;
use Twig\Extension\CoreExtension;
use Twig\Loader\FilesystemLoader;

class TwigEnvironmentFactory
{
    public function __invoke(): Environment
    {
        $loader = new FilesystemLoader(__DIR__ . '/../../templates');
        $twig = new Environment($loader);
        $twig->getExtension(CoreExtension::class)->setNumberFormat(0, ',', '.');
        return $twig;
    }
}
