<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Twig\Environment;

class BlackMarketHandler
{
    public function __construct(
        private Environment $twigEnvironment
    ) {
    }

    public function handler()
    {
        $htmlContent = $this->twigEnvironment->render('showData.html.twig');
        return new HtmlResponse($htmlContent);
    }
}
