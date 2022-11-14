<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use InvalidArgumentException;
use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\Service\BlackMarketCraftingService;
use Twig\Environment;

class BlackMarketCraftingHandler
{
    public function __construct(
        private Environment $twigEnvironment,
        private BlackMarketCraftingService $blackMarketCraftingService,
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
                $cityData = $this->blackMarketCraftingService->getDataForCity(
                    $itemCity,
                    $weight,
                    $rrr,
                    $resourceCity,
                    $order
                );
            } catch (InvalidArgumentException $invalidArgumentExceptionException) {
                $alertMessage = $invalidArgumentExceptionException->getMessage();
            }
        }
        $htmlContent = $this->twigEnvironment->render('BlackMarketCrafting.html.twig', [
            'dataArray' => $cityData,
            'infoService' => $this->blackMarketCraftingService,
            'alertMessage' => $alertMessage,
            'rates' => $this->blackMarketCraftingService->getCraftingRates(),
        ]);
        return new HtmlResponse($htmlContent);
    }
}
