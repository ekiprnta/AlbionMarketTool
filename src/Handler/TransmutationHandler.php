<?php

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\Service\TimeService;
use MZierdt\Albion\Service\TransmutationService;
use Twig\Environment;

class TransmutationHandler
{
    public function __construct(
        private readonly Environment $twigEnvironment,
        private readonly TransmutationService $transmutationService,
    ) {
    }

    public function handler(): HtmlResponse
    {
        $cityData = [];
        $alertMessage = null;

        if (! empty($_GET)) {
            $city = $_GET['city'];
            try {
                $cityData = $this->transmutationService->getTransmutationByCity($city);
            } catch (\InvalidArgumentException $invalidArgumentException) {
                $alertMessage = $invalidArgumentException->getMessage();
            }
//            dd($cityData);
        }

        $htmlContent = $this->twigEnvironment->render('Transmutation.html.twig', [
            'dataArray' => $cityData,
            'alertMessage' => $alertMessage,
            'timeThreshold' => TimeService::getFiveDaysAgo(new \DateTimeImmutable()),
        ]);
        return new HtmlResponse($htmlContent);
    }
}
