<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use InvalidArgumentException;
use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\AlbionMarket\BlackMarketCraftingService;
use MZierdt\Albion\AlbionMarket\CraftingService;
use MZierdt\Albion\repositories\AdvancedRepository\BlackMarketCraftingRepository;
use MZierdt\Albion\Service\TimeService;
use Twig\Environment;

class BlackMarketCraftingHandler
{
    private const FOCUS_RETURN_RATE = 47.9;

    public function __construct(
        private readonly Environment $twigEnvironment,
        private readonly BlackMarketCraftingRepository $bmcRepository,
        private readonly BlackMarketCraftingService $blackMarketCraftingService,
    ) {
    }

    public function handler(): HtmlResponse
    {
        $cityData = [];
        $alertMessage = null;
        if (! empty($_GET)) {
            $itemCity = $_GET['itemCity'];
            $percentage = (float) $_GET['rrr'];
            if (empty($percentage)) {
                $percentage = self::FOCUS_RETURN_RATE;
            }
            try {
                $cityData = $this->bmcRepository->getAllBmCraftingByCity($itemCity);
            } catch (InvalidArgumentException $invalidArgumentExceptionException) {
                $alertMessage = $invalidArgumentExceptionException->getMessage();
            }
            foreach ($cityData as $bmcEntity) {
                $this->blackMarketCraftingService->calculateProfitByPercentage($bmcEntity, $percentage);
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
