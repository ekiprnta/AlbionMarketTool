<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\repositories\AdvancedRepository\BlackMarketTransportingRepository;
use MZierdt\Albion\Service\TimeService;
use Twig\Environment;

class BlackMarketTransportingHandler
{
    public function __construct(
        private readonly Environment $twigEnvironment,
        private readonly BlackMarketTransportingRepository $bmtRepository,
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
                $cityData = $this->bmtRepository->getAllTransportingByCity($itemCity);
            } catch (\InvalidArgumentException $invalidArgumentException) {
                $alertMessage = $invalidArgumentException->getMessage();
            }
        }

        $htmlContent = $this->twigEnvironment->render('BlackMarketTransport.html.twig', [
            'dataArray' => $cityData,
            'alertMessage' => $alertMessage,
            'timeThreshold' => TimeService::getFiveDaysAgo(new \DateTimeImmutable())
        ]);
        return new HtmlResponse($htmlContent);
    }
}
