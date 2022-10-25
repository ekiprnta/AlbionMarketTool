<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\Service\CalculatorService;
use MZierdt\Albion\Service\UploadService;
use Twig\Environment;

class BlackMarketHandler
{
    public function __construct(
        private Environment $twigEnvironment,
        private CalculatorService $calculatorService,
        private UploadService $uploadService,
    ) {
    }

    public function handler(): HtmlResponse
    {
        if (! empty($_POST['updatePrices'])) {
            $this->uploadService->uploadRefreshedPrices();
        }

        $amount = 1;
        $cityData = $this->calculatorService->getDataForCity('Fort Sterling');

        $htmlContent = $this->twigEnvironment->render('calculateBlackMarket.html.twig', [
            'dataArray' => $cityData,
            'amount' => $amount,
        ]);
        return new HtmlResponse($htmlContent);
    }
}
