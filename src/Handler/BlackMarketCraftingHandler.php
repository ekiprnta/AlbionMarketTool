<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use InvalidArgumentException;
use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\AlbionMarket\BlackMarketCraftingService;
use MZierdt\Albion\Entity\AdvancedEntities\BlackMarketCraftingEntity;
use MZierdt\Albion\Entity\MaterialEntity;
use MZierdt\Albion\repositories\AdvancedRepository\BlackMarketCraftingRepository;
use MZierdt\Albion\repositories\MaterialRepository;
use MZierdt\Albion\Service\TimeService;
use Twig\Environment;

class BlackMarketCraftingHandler
{
    private const FOCUS_RETURN_RATE = 47.9;

    public function __construct(
        private readonly Environment $twigEnvironment,
        private readonly BlackMarketCraftingRepository $bmcRepository,
        private readonly BlackMarketCraftingService $blackMarketCraftingService,
        private readonly MaterialRepository $materialRepository,
    ) {
    }

    public function handler(): HtmlResponse
    {
        $cityData = [];
        $tome = new MaterialEntity();
        $alertMessage = null;
        if (! empty($_GET)) {
            $itemCity = $_GET['itemCity'];
            $percentage = (float) $_GET['rrr'];
            $bonusResource = true;
            if (empty($_GET['bonusResource'])) {
                $bonusResource = false;
            }
            if (empty($percentage)) {
                $percentage = self::FOCUS_RETURN_RATE;
            }
            try {
                $cityData = $this->bmcRepository->getAllBmCraftingByCity($itemCity, $bonusResource);
                $tome = $this->materialRepository->getTomesByCity($itemCity);
            } catch (InvalidArgumentException $invalidArgumentExceptionException) {
                $alertMessage = $invalidArgumentExceptionException->getMessage();
            }
            /** @var BlackMarketCraftingEntity $bmcEntity */
            foreach ($cityData as $bmcEntity) {
                $this->blackMarketCraftingService->calculateProfitByPercentage($bmcEntity, $percentage, $tome);
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
