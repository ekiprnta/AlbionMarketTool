<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\Service\CapesCraftingService;
use Twig\Environment;

class CapesCraftingHandler
{
    public function __construct(
        private readonly Environment $twigEnvironment,
        private readonly CapesCraftingService $capesCraftingService,
    ) {
    }

    public function handler(): HtmlResponse
    {
        $this->capesCraftingService->getCapesByCity('Martlock');

        $htmlContent = $this->twigEnvironment->render('CapesCrafting.html.twig');
        return new HtmlResponse($htmlContent);
    }
}