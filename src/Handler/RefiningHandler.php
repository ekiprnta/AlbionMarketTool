<?php

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\Service\RefiningService;
use Twig\Environment;

class RefiningHandler
{
    public function __construct(
        private Environment $twigEnvironment,
        private RefiningService $refiningService,
    ) {
    }

    public function handler()
    {
        $cityData = [];
        $alertMessage = null;

        if (! empty($_GET)) {
            $request = $_GET;
            $itemCity = $request['itemCity'];
            $percentage = (float) $request['rrr'];
            try {
                $cityData = $this->refiningService->getRefiningForCity($itemCity, $percentage);
            } catch (\InvalidArgumentException $invalidArgumentException) {
                $alertMessage = $invalidArgumentException->getMessage();
            }
        }
//    dd($cityData);
        $htmlContent = $this->twigEnvironment->render('Refining.html.twig', [
            'dataArray' => $cityData,
            'alertMessage' => $alertMessage,
            'rates' => $this->refiningService->getRefiningRates(),
        ]);
        return new HtmlResponse($htmlContent);
    }
}
