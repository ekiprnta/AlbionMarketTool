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
    ) {
    }

    public function handler(): HtmlResponse
    {
        $fortSterlingResource = $this->resourceRepository->getResourcesByCity(self::CITY_FORTSTERLING);
        $lymhurstResource = $this->resourceRepository->getResourcesByCity(self::CITY_LYMHURST);
        $bridgewatchResource = $this->resourceRepository->getResourcesByCity(self::CITY_BRIDGEWATCH);
        $martlockResource = $this->resourceRepository->getResourcesByCity(self::CITY_MARTLOCK);
        $thetfordResource = $this->resourceRepository->getResourcesByCity(self::CITY_THETFORD);

        $fortSterlingItems = $this->itemRepository->getBlackMarketItemsFromCity(self::CITY_FORTSTERLING);
        $lymhurstItems = $this->itemRepository->getBlackMarketItemsFromCity(self::CITY_LYMHURST);
        $bridgewatchItems = $this->itemRepository->getBlackMarketItemsFromCity(self::CITY_BRIDGEWATCH);
        $martlockItems = $this->itemRepository->getBlackMarketItemsFromCity(self::CITY_MARTLOCK);
        $thetfordItems = $this->itemRepository->getBlackMarketItemsFromCity(self::CITY_THETFORD);

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
