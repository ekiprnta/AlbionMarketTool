<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\Service\ApiService;
use MZierdt\Albion\Service\UploadService;
use Twig\Environment;

class AdminHandler
{
    public function __construct(
        private Environment $twigEnvironment,
        private UploadService $uploadService,
    ) {
    }

    public function handler(): HtmlResponse
    {
        if (! empty($_POST['updateJournals'])) {
            $this->uploadService->updateJournalPricesInAlbionDb();
        }
        if (! empty($_POST['updateResources'])) {
            $this->uploadService->updateResourcePricesInAlbionDb();
        }
        if (! empty($_POST['updateItemsBlackMarket'])) {
            $this->uploadService->updatePricesInCityDependingOnCLass($_POST['class']);
        }

        $htmlContent = $this->twigEnvironment->render('admin.html.twig');
        return new HtmlResponse($htmlContent);
    }
}
