<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\repositories\ResourceRepository;
use MZierdt\Albion\repositories\ResourceUploadRepository;
use MZierdt\Albion\repositories\UploadCsvRepository;
use Twig\Environment;

class ShowResourcePriceHandler
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
        $fortSterlingResources = $this->resourceRepository->getAllResourcesFromCity(self::CITY_FORTSTERLING);
        $lymhurstResources = $this->resourceRepository->getAllResourcesFromCity(self::CITY_LYMHURST);
        $bridgewatchResources = $this->resourceRepository->getAllResourcesFromCity(self::CITY_BRIDGEWATCH);
        $martlockResources = $this->resourceRepository->getAllResourcesFromCity(self::CITY_MARTLOCK);
        $thetfordResources = $this->resourceRepository->getAllResourcesFromCity(self::CITY_THETFORD);

        $fortSterlingItems = $this->itemRepository->getItemsAsItemEntityFromBonusCity(self::CITY_FORTSTERLING);
        $lymhurstItems = $this->itemRepository->getItemsAsItemEntityFromBonusCity(self::CITY_LYMHURST);
        $bridgewatchItems = $this->itemRepository->getItemsAsItemEntityFromBonusCity(self::CITY_BRIDGEWATCH);
        $martlockItems = $this->itemRepository->getItemsAsItemEntityFromBonusCity(self::CITY_MARTLOCK);
        $thetfordItems = $this->itemRepository->getItemsAsItemEntityFromBonusCity(self::CITY_THETFORD);

        $allCitiesResource = [
            [$fortSterlingResources, $fortSterlingItems],
            [$lymhurstResources, $lymhurstItems],
            [$bridgewatchResources, $bridgewatchItems],
            [$martlockResources, $martlockItems],
            [$thetfordResources, $thetfordItems]
        ];

        $htmlContent = $this->twigEnvironment->render('showData.html.twig', [
            'data' => $allCitiesResource,
        ]);
        return new HtmlResponse($htmlContent);
    }
}
