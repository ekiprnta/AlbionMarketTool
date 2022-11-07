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
            $this->uploadService->updateWarriorPricesInAlbionDbByCity(ApiService::CITY_BLACKMARKET);
        }
        if (! empty($_POST['updateItemsFortSterling'])) {
            $this->uploadService->updateWarriorPricesInAlbionDbByCity(ApiService::CITY_FORTSTERLING);
        }
        if (! empty($_POST['updateItemsLymhurst'])) {
            $this->uploadService->updateWarriorPricesInAlbionDbByCity(ApiService::CITY_LYMHURST);
        }
        if (! empty($_POST['updateItemsBridgewatch'])) {
            $this->uploadService->updateWarriorPricesInAlbionDbByCity(ApiService::CITY_BRIDGEWATCH);
        }
        if (! empty($_POST['updateItemsMartlock'])) {
            $this->uploadService->updateWarriorPricesInAlbionDbByCity(ApiService::CITY_MARTLOCK);
        }
        if (! empty($_POST['updateItemsThetford'])) {
            $this->uploadService->updateWarriorPricesInAlbionDbByCity(ApiService::CITY_THETFORD);
        }

        $htmlContent = $this->twigEnvironment->render('admin.html.twig');
        return new HtmlResponse($htmlContent);
    }
}
