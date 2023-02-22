<?php

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\repositories\AdvancedRepository\TransmutationRepository;
use MZierdt\Albion\Service\TimeService;
use Twig\Environment;

class TransmutationHandler
{
    public function __construct(
        private readonly Environment $twigEnvironment,
        private readonly TransmutationRepository $transmutationRepository,
    ) {
    }

    public function handler(): HtmlResponse
    {
        $cityData = [];
        $alertMessage = null;

        if (! empty($_GET)) {
            $city = $_GET['city'];
            try {
                $cityData = $this->transmutationRepository->getAllTransmutationByCity($city);
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
