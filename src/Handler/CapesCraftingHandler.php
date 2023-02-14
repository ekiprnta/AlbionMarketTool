<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\Service\CapesCraftingService;
use Twig\Environment;

class CapesCraftingHandler
{
    public function __construct(
        private readonly Environment $twigEnvironment,
        private readonly CapesCraftingService $capesCraftingService,
    ) {
    }

    public function handler(): HtmlResponse
    {
        $cityData = [];
        $alertMessage = null;
        if (! empty($_GET)) {
            $city = $_GET['itemCity'];
            try {
                $cityData = $this->capesCraftingService->getCapesByCity($city);
            } catch (\Exception $exception) {
                $alertMessage = $exception->getMessage();
            }
        }

        $htmlContent = $this->twigEnvironment->render(
            'CapesCrafting.html.twig',
            [
                'dataArray' => $cityData,
                'alertMessage' => $alertMessage,
            ]
        );
        return new HtmlResponse($htmlContent);
    }
}
