<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\Service\CalculatorService;
use Twig\Environment;

class BlackMarketHandler
{
    public function __construct(
        private Environment $twigEnvironment,
        private CalculatorService $calculatorService,
    ) {
    }

    public function handler()
    {
        $amount = 1;
        $cityData = $this->calculatorService->getDataForCity('Martlock');

        $htmlContent = $this->twigEnvironment->render('calculateBlackMarket.html.twig', [
            'dataArray' => $cityData,
            'amount' => $amount,
        ]);
        return new HtmlResponse($htmlContent);
    }
}
