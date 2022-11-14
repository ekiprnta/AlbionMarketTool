<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\Service\BlackMarketTransportingService;
use Twig\Environment;

class BlackMarketTransportingHandler
{
    public function __construct(
        private Environment $twigEnvironment,
        private BlackMarketTransportingService $blackMarketTransportingService,
    ) {
    }

    public function handler(): HtmlResponse
    {
        $cityData = [];
        $alertMessage = null;
        if (! empty($_GET)) {
            $request = $_GET;
            $itemCity = $request['itemCity'];
            unset($request['itemCity']);
            $weight = (int) $request['weight'];
            unset($request['weight']);
            try {
                $cityData = $this->blackMarketTransportingService->getDataForCity(
                    $itemCity,
                    $weight,
                    array_filter($request)
                );
            } catch (\InvalidArgumentException $invalidArgumentException) {
                $alertMessage = $invalidArgumentException->getMessage();
            }
        }

        $htmlContent = $this->twigEnvironment->render('BlackMarketTransport.html.twig', [
            'dataArray' => $cityData,
            'alertMessage' => $alertMessage,
        ]);
        return new HtmlResponse($htmlContent);
    }
}
