<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\Service\EnchantingService;
use Twig\Environment;

class EnchantingHandler
{
    public function __construct(
        private readonly Environment $environment,
        private readonly EnchantingService $enchantingService,
    ) {
    }

    public function handler(): HtmlResponse
    {
        $htmlContent = $this->environment->render('Enchanting.html.twig', []);
        return new HtmlResponse($htmlContent);
    }
}