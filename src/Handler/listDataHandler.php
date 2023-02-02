<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\Service\ListDataService;
use Twig\Environment;

class listDataHandler
{
    public function __construct(
        private readonly Environment $twigEnvironment,
        private readonly ListDataService $listDataHandler,
    ) {
    }

    public function handler(): HtmlResponse
    {
        $allResources = $this->listDataHandler->getAllResources();
        $allRawResources = $this->listDataHandler->getAllRawResources();

        $htmlContent = $this->twigEnvironment->render('showData.html.twig', [
            'resources' => $allResources,
            'resource' => $allResources,
        ]);
        return new HtmlResponse($htmlContent);
    }
}
