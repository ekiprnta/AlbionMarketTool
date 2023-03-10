<?php

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\AlbionMarket\CraftingService;
use MZierdt\Albion\AlbionMarket\RefiningService;
use MZierdt\Albion\repositories\AdvancedRepository\RefiningRepository;
use MZierdt\Albion\Service\TimeService;
use Twig\Environment;

class RefiningHandler
{
    private const FOKUS_RETURN_RATE = 53.9;

    public function __construct(
        private readonly Environment $twigEnvironment,
        private readonly RefiningRepository $refiningRepository,
        private readonly RefiningService $refiningService,
    ) {
    }

    public function handler(): HtmlResponse
    {
        $cityData = [];
        $alertMessage = null;

        if (! empty($_GET)) {
            $request = $_GET;
            $itemCity = $request['itemCity'];
            $percentage = (float) $request['rrr'];
            if (empty($percentage)) {
                $percentage = self::FOKUS_RETURN_RATE;
            }
            try {
                $cityData = $this->refiningRepository->getAllRefiningByCity($itemCity);
            } catch (\InvalidArgumentException $invalidArgumentException) {
                $alertMessage = $invalidArgumentException->getMessage();
            }
            foreach ($cityData as $refiningEntity) {
                $this->refiningService->calculateProfitByPercentage($refiningEntity, $percentage);
            }
        }

        $htmlContent = $this->twigEnvironment->render('Refining.html.twig', [
            'dataArray' => $cityData,
            'alertMessage' => $alertMessage,
            'timeThreshold' => TimeService::getFiveDaysAgo(new \DateTimeImmutable()),
        ]);
        return new HtmlResponse($htmlContent);
    }
}
