<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\repositories\AdvancedRepository\ListDataRepository;
use Twig\Environment;

class ListDataHandler
{
    public function __construct(
        private readonly Environment $twigEnvironment,
        private readonly ListDataRepository $listDataRepository,
    ) {
    }

    public function handler(): HtmlResponse
    {
        $allResources = [];
        if (! empty($_GET)) {
            $allResources = $this->listDataRepository->getAllRefiningByType($_GET['refined']);
        }

        $htmlContent = $this->twigEnvironment->render('listData.html.twig', [
            'resources' => $allResources,
        ]);
        return new HtmlResponse($htmlContent);
    }
}
