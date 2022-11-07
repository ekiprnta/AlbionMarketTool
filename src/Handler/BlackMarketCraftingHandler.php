<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use InvalidArgumentException;
use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\Service\ApiService;
use MZierdt\Albion\Service\BlackMarketCraftingService;
use MZierdt\Albion\Service\UploadService;
use Twig\Environment;

class BlackMarketCraftingHandler
{
    public function __construct(
        private Environment $twigEnvironment,
        private BlackMarketCraftingService $calculatorService,
    ) {
    }

    public function handler(): HtmlResponse
    {
        $cityData = [];
        $alertMessage = null;
        if (! empty($_GET)) {
            $itemCity = $_GET['itemCity'];
            $resourceCity = $_GET['resourceCity'];
            $weight = (int) $_GET['weight'];
            $rrr = (float) $_GET['rrr'];
            $order = $_GET['order'];
            try {
                $cityData = $this->calculatorService->getDataForCity($itemCity, $weight, $rrr, $resourceCity, $order);
            } catch (InvalidArgumentException $invalidArgumentExceptionException) {
                $alertMessage = $invalidArgumentExceptionException->getMessage();
            }
        }
        $htmlContent = $this->twigEnvironment->render('BlackMarketCrafting.html.twig', [
            'dataArray' => $cityData,
            'infoService' => $this->calculatorService,
            'alertMessage' => $alertMessage,
        ]);
        return new HtmlResponse($htmlContent);
    }
}
