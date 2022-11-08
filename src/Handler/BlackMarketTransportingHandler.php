<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\Service\BlackMarketTransportingService;
use Twig\Environment;

class BlackMarketTransportingHandler
{
    public function __construct(
        private Environment $twigEnvironment,
        private BlackMarketTransportingService $blackMarketTransportingService,
    ) {
    }

    public function handler(): HtmlResponse
    {
        $this->blackMarketTransportingService->getDataForCity('Martlock', 2000);

        $htmlContent = $this->twigEnvironment->render('BlackMarketTransport.html.twig');
        return new HtmlResponse($htmlContent);
    }
}
