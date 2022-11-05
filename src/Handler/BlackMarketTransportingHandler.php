<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Twig\Environment;

class BlackMarketTransportingHandler
{
    public function __construct(
        private Environment $twigEnvironment
    ) {
    }

    public function handler(): HtmlResponse
    {
        $htmlContent = $this->twigEnvironment->render('BlackMarketTransport.html.twig');
        return new HtmlResponse($htmlContent);
    }
}
