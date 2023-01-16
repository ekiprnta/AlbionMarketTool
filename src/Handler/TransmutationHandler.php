<?php

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\Service\TransmutationService;
use Twig\Environment;

class TransmutationHandler
{
    public function __construct(
        private Environment $twigEnvironment,
        private TransmutationService $transmutationService,
    ) {
    }

    public function handler(): HtmlResponse
    {
        $cityData = [];
        $alertMessage = null;

        $city = "Martlock";
        $cityData = $this->transmutationService->getTransmutationByCity($city);

        dd($cityData);

        $htmlContent = $this->twigEnvironment->render('Transmutation.html.twig', [
            'dataArray' => $cityData,
            'alertMessage' => $alertMessage,
            'rates' => $this->refiningService->getRefiningRates(),
        ]);
        return new HtmlResponse($htmlContent);
    }
}