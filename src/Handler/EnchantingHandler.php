<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\repositories\AdvancedRepository\EnchantingRepository;
use MZierdt\Albion\Service\TimeService;
use Twig\Environment;

class EnchantingHandler
{
    public function __construct(
        private readonly Environment $environment,
        private readonly EnchantingRepository $enchantingRepository,
    ) {
    }

    public function handler(): HtmlResponse
    {
        $cityData = [];
        $alertMessage = null;
        if (! empty($_GET)) {
            $city = $_GET['itemCity'];
            try {
                $cityData = $this->enchantingRepository->getAllEnchantingByCity($city);
            } catch (\Exception $exception) {
                $alertMessage = $exception->getMessage();
            } // TODo get avg amount and build better Quotient
        }

        $htmlContent = $this->environment->render('Enchanting.html.twig', [
            'dataArray' => $cityData,
            'alertMessage' => $alertMessage,
            'timeThreshold' => TimeService::getFiveDaysAgo(new \DateTimeImmutable()),
        ]);
        return new HtmlResponse($htmlContent);
    }
}
