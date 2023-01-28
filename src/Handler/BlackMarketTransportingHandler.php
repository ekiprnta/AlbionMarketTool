<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\Service\BlackMarketTransportingService;
use Twig\Environment;

class BlackMarketTransportingHandler
{
    public function __construct(
        private readonly Environment $twigEnvironment,
        private readonly BlackMarketTransportingService $blackMarketTransportingService,
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
            try {
                $cityData = $this->blackMarketTransportingService->getDataForCity(
                    $itemCity,
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