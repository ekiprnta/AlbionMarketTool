<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use InvalidArgumentException;
use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\AlbionMarket\BlackMarketCraftingService;
use MZierdt\Albion\AlbionMarket\CraftingService;
use MZierdt\Albion\Service\TimeService;
use Twig\Environment;

class BlackMarketCraftingHandler
{
    public function __construct(
        private readonly Environment $twigEnvironment,
        private readonly BlackMarketCraftingService $blackMarketCraftingService,
        private readonly CraftingService $craftingService,
    ) {
    }

    public function handler(): HtmlResponse
    {
        $cityData = [];
        $alertMessage = null;
        if (! empty($_GET)) {
            $itemCity = $_GET['itemCity'];
            $percentage = (float) $_GET['rrr'];
            try {
                $cityData = $this->craftingService->getAllBmCraftingByCity($itemCity, $percentage);
            } catch (InvalidArgumentException $invalidArgumentExceptionException) {
                $alertMessage = $invalidArgumentExceptionException->getMessage();
            }
        }

        $htmlContent = $this->twigEnvironment->render('BlackMarketCrafting.html.twig', [
            'dataArray' => $cityData,
            'alertMessage' => $alertMessage,
            'rates' => $this->blackMarketCraftingService->getCraftingRates(),
            'timeThreshold' => TimeService::getFiveDaysAgo(new \DateTimeImmutable()),
        ]);
        return new HtmlResponse($htmlContent);
    }
}
