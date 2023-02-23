<?php

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\repositories\AdvancedRepository\RefiningRepository;
use MZierdt\Albion\Service\TimeService;
use Twig\Environment;

class RefiningHandler
{
    public function __construct(
        private readonly Environment $twigEnvironment,
        private readonly RefiningRepository $refiningRepository,
    ) {
    }

    public function handler(): HtmlResponse
    {
        $cityData = [];
        $alertMessage = null;

        if (! empty($_GET)) {
            $request = $_GET;
            $itemCity = $request['itemCity'];
            try {
                $cityData = $this->refiningRepository->getAllRefiningByCity($itemCity);
            } catch (\InvalidArgumentException $invalidArgumentException) {
                $alertMessage = $invalidArgumentException->getMessage();
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
