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

        $cityData = [];
        if(!empty($_GET))
        {
            $cityData = $this->calculatorService->getDataForCity($_GET['city'], (int)$_GET['weight'],(float) $_GET['rrr']);
        }
        $htmlContent = $this->twigEnvironment->render('calculateBlackMarket.html.twig', [
            'dataArray' => $cityData,
        ]);
        return new HtmlResponse($htmlContent);
    }
}
