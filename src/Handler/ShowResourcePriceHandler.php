<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\Service\ApiService;
use Twig\Environment;

class ShowResourcePriceHandler
{
    public function __construct(
        private Environment $twigEnvironment,
        private ApiService $apiService
    ) {
    }

    public function handler(): HtmlResponse
    {
        echo $this->apiService->getResource('planks');

        $htmlContent = $this->twigEnvironment->render('test.html.twig');
        return new HtmlResponse($htmlContent);
    }
}