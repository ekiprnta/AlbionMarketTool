<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\Service\ApiService;
use MZierdt\Albion\Service\UploadService;
use Twig\Environment;

class AdminHandler
{
    public function __construct(
        private Environment $twigEnvironment,
    ) {
    }

    public function handler(): HtmlResponse
    {
        $htmlContent = $this->twigEnvironment->render('admin.html.twig');
        return new HtmlResponse($htmlContent);
    }
}
