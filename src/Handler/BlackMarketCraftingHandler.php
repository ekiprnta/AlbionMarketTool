<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use InvalidArgumentException;
use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\Service\BlackMarketCraftingService;
use MZierdt\Albion\Service\TimeService;
use Twig\Environment;

class BlackMarketCraftingHandler
{
    public function __construct(
        private readonly Environment $twigEnvironment,
        private readonly BlackMarketCraftingService $blackMarketCraftingService,
    ) {
    }

    public function handler(): HtmlResponse
    {
        $cityData = [];
        $alertMessage = null;
        if (! empty($_GET)) {
            $itemCity = $_GET['itemCity'];
            $resourceCity = $_GET['resourceCity'];
            $rrr = (float) $_GET['rrr'];
            $feeProHundredNutrition = (int) $_GET['craftingFee'];
            $order = $_GET['order'];
            try {
                $cityData = $this->blackMarketCraftingService->getDataForCity(
                    $itemCity,
                    $rrr,
                    $feeProHundredNutrition,
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
            'timeThreshold' => TimeService::getFiveDaysAgo(new \DateTimeImmutable()),
        ]);
        return new HtmlResponse($htmlContent);
    }
}
