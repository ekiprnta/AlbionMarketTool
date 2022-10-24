<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\repositories\ResourceRepository;
use Twig\Environment;

class listDataHandler
{
    private const CITY_FORTSTERLING = 'Fort Sterling';
    private const CITY_LYMHURST = 'Lymhurst';
    private const CITY_BRIDGEWATCH = 'Bridgewatch';
    private const CITY_MARTLOCK = 'Martlock';
    private const CITY_THETFORD = 'Thetford';

    public function __construct(
        private Environment $twigEnvironment,
        private ItemRepository $itemRepository,
        private ResourceRepository $resourceRepository,
        private UploadService $uploadHandler,
    ) {
    }

    public function handler(): HtmlResponse
    {
        $this->uploadHandler->uploadRefreshedPrices();
        die();

        $fortSterlingResource = $this->resourceRepository->getAllResourcesFromCity(self::CITY_FORTSTERLING);
        $lymhurstResource = $this->resourceRepository->getAllResourcesFromCity(self::CITY_LYMHURST);
        $bridgewatchResource = $this->resourceRepository->getAllResourcesFromCity(self::CITY_BRIDGEWATCH);
        $martlockResource = $this->resourceRepository->getAllResourcesFromCity(self::CITY_MARTLOCK);
        $thetfordResource = $this->resourceRepository->getAllResourcesFromCity(self::CITY_THETFORD);

        $fortSterlingItems = $this->itemRepository->getItemsAsItemEntityFromBonusCity(self::CITY_FORTSTERLING);
        $lymhurstItems = $this->itemRepository->getItemsAsItemEntityFromBonusCity(self::CITY_LYMHURST);
        $bridgewatchItems = $this->itemRepository->getItemsAsItemEntityFromBonusCity(self::CITY_BRIDGEWATCH);
        $martlockItems = $this->itemRepository->getItemsAsItemEntityFromBonusCity(self::CITY_MARTLOCK);
        $thetfordItems = $this->itemRepository->getItemsAsItemEntityFromBonusCity(self::CITY_THETFORD);

        $allCitiesResource = [
            'fortSterling' => $fortSterlingResource,
            'lymhurst' => $lymhurstResource,
            'bridgewatch' => $bridgewatchResource,
            'martlock' => $martlockResource,
            'thetford' => $thetfordResource,
        ];
        $allCitiesItems = [
            'fortSterling' => $fortSterlingItems,
            'lymhurst' => $lymhurstItems,
            'bridgewatch' => $bridgewatchItems,
            'martlock' => $martlockItems,
            'thetford' => $thetfordItems,
        ];
        $htmlContent = $this->twigEnvironment->render('showData.html.twig', [
            'allCitiesResource' => $allCitiesResource,
            'allCitiesItems' => $allCitiesItems,
        ]);
        return new HtmlResponse($htmlContent);
    }
}
