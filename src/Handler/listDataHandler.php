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
        $allItems = $this->listDataHandler->getAllItems();


        $htmlContent = $this->twigEnvironment->render('showData.html.twig', [
            'items' => $allItems,
        ]);
        return new HtmlResponse($htmlContent);
    }
}
