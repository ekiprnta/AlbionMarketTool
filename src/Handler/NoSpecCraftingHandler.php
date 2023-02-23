<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\repositories\AdvancedRepository\NoSpecRepository;
use MZierdt\Albion\Service\TimeService;
use Twig\Environment;

class NoSpecCraftingHandler
{
    public function __construct(
        private readonly Environment $twigEnvironment,
        private readonly NoSpecRepository $noSpecRepository,
    ) {
    }

    public function handler(): HtmlResponse
    {
        $cityData = [];
        $alertMessage = null;
        if (! empty($_GET)) {
            $city = $_GET['itemCity'];
            try {
                $cityData = $this->noSpecRepository->getAllNoSpecCraftingByCity($city);
            } catch (\Exception $exception) {
                $alertMessage = $exception->getMessage();
            }
        }

        $htmlContent = $this->twigEnvironment->render(
            'NoSpecCrafting.html.twig',
            [
                'dataArray' => $cityData,
                'alertMessage' => $alertMessage,
                'timeThreshold' => TimeService::getFiveDaysAgo(new \DateTimeImmutable()),
            ]
        );
        return new HtmlResponse($htmlContent);
    }
}
