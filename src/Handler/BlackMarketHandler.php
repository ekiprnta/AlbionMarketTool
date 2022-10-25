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
        $city ='';
        $rrr = 'Resource Return Rate in %';
        $weight = 'weight in kg';
        if(!empty($_GET))
        {
            $city = $_GET['city'];
            $weight = (int)$_GET['weight'];
            $rrr = (float) $_GET['rrr'];
            $cityData = $this->calculatorService->getDataForCity($city, $weight, $rrr);
        }
        $htmlContent = $this->twigEnvironment->render('calculateBlackMarket.html.twig', [
            'dataArray' => $cityData,
            'city' => $city,
            'weight' => $weight,
            'rrr' => $rrr,
        ]);
        return new HtmlResponse($htmlContent);
    }
}
