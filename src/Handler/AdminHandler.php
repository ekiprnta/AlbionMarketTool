<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Twig\Environment;

class AdminHandler
{
    public function __construct(
        private readonly Environment $twigEnvironment,
    ) {
    }

    public function handler(): HtmlResponse
    {
        $now = new \DateTimeImmutable();

        $htmlContent = $this->twigEnvironment->render('admin.html.twig',);
        return new HtmlResponse($htmlContent);
    }
}
