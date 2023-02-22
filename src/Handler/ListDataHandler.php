<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\AlbionMarket\ListDataService;
use Twig\Environment;

class ListDataHandler
{
    public function __construct(
        private readonly Environment $twigEnvironment,
        private readonly ListDataService $listDataHandler,
    ) {
    }

    public function handler(): HtmlResponse
    {
        $allResources = [];
        if (! empty($_GET)) {
            $allResources = $this->listDataHandler->getResources($_GET['refined']);
        }

        $htmlContent = $this->twigEnvironment->render('showData.html.twig', [
            'resources' => $allResources,
        ]);
        return new HtmlResponse($htmlContent);
    }
}
