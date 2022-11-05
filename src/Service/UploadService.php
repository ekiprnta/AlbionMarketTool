<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\JournalEntity;
use MZierdt\Albion\Entity\ResourceEntity;
use MZierdt\Albion\repositories\UploadRepository;

class UploadService
{
    private array $itemList = [
        [ItemEntity::ITEM_WARRIOR_HELMET, ItemEntity::CLASS_WARRIOR],
        [ItemEntity::ITEM_WARRIOR_ARMOR, ItemEntity::CLASS_WARRIOR],
        [ItemEntity::ITEM_WARRIOR_BOOTS, ItemEntity::CLASS_WARRIOR],
        [ItemEntity::ITEM_WARRIOR_SWORD, ItemEntity::CLASS_WARRIOR],
        [ItemEntity::ITEM_WARRIOR_AXE, ItemEntity::CLASS_WARRIOR],
        [ItemEntity::ITEM_WARRIOR_MACE, ItemEntity::CLASS_WARRIOR],
        [ItemEntity::ITEM_WARRIOR_HAMMER, ItemEntity::CLASS_WARRIOR],
        [ItemEntity::ITEM_WARRIOR_WAR_GLOVE, ItemEntity::CLASS_WARRIOR],
        [ItemEntity::ITEM_WARRIOR_CROSSBOW, ItemEntity::CLASS_WARRIOR],
        [ItemEntity::ITEM_WARRIOR_SHIELD, ItemEntity::CLASS_WARRIOR],

        [ItemEntity::ITEM_MAGE_HELMET, ItemEntity::CLASS_MAGE],
        [ItemEntity::ITEM_MAGE_ARMOR, ItemEntity::CLASS_MAGE],
        [ItemEntity::ITEM_MAGE_BOOTS, ItemEntity::CLASS_MAGE],
        [ItemEntity::ITEM_MAGE_FIRE_STAFF, ItemEntity::CLASS_MAGE],
        [ItemEntity::ITEM_MAGE_HOLY_STAFF, ItemEntity::CLASS_MAGE],
        [ItemEntity::ITEM_MAGE_ARCANE_STAFF, ItemEntity::CLASS_MAGE],
        [ItemEntity::ITEM_MAGE_FROST_STAFF, ItemEntity::CLASS_MAGE],
        [ItemEntity::ITEM_MAGE_CURSE_STAFF, ItemEntity::CLASS_MAGE],
        [ItemEntity::ITEM_MAGE_TOME_STAFF, ItemEntity::CLASS_MAGE],

        [ItemEntity::ITEM_HUNTER_HELMET, ItemEntity::CLASS_HUNTER],
        [ItemEntity::ITEM_HUNTER_ARMOR, ItemEntity::CLASS_HUNTER],
        [ItemEntity::ITEM_HUNTER_BOOTS, ItemEntity::CLASS_HUNTER],
        [ItemEntity::ITEM_HUNTER_BOW, ItemEntity::CLASS_HUNTER],
        [ItemEntity::ITEM_HUNTER_SPEAR, ItemEntity::CLASS_HUNTER],
        [ItemEntity::ITEM_HUNTER_NATURE_STAFF, ItemEntity::CLASS_HUNTER],
        [ItemEntity::ITEM_HUNTER_DAGGER, ItemEntity::CLASS_HUNTER],
        [ItemEntity::ITEM_HUNTER_QUARTERSTAFF, ItemEntity::CLASS_HUNTER],
        [ItemEntity::ITEM_HUNTER_TORCH, ItemEntity::CLASS_HUNTER],
    ];

    public function __construct(
        private ApiService $apiService,
        private UploadRepository $uploadRepository,
        private ItemHelper $itemHelper
    ) {
    }

    public function uploadJournalsIntoEmptyDb(): void
    {
        $journals = $this->getAdjustedJournals();
        $this->uploadRepository->loadJournalsIntoDatabase($journals['warrior']);
        $this->uploadRepository->loadJournalsIntoDatabase($journals['mage']);
        $this->uploadRepository->loadJournalsIntoDatabase($journals['hunter']);
    }

    public function uploadItemsIntoEmptyDb(): void
    {
        $items = $this->getAdjustedItems();

        $this->uploadRepository->loadItemsIntoDatabase($items['warrior']);
        $this->uploadRepository->loadItemsIntoDatabase($items['mage']);
        $this->uploadRepository->loadItemsIntoDatabase($items['hunter']);
    }

    public function uploadResourceIntoEmptyDb(): void
    {
        $resources = $this->getAdjustedResources();

        $this->uploadRepository->loadResourcesIntoDatabase($resources['metalBar']);
        $this->uploadRepository->loadResourcesIntoDatabase($resources['planks']);
        $this->uploadRepository->loadResourcesIntoDatabase($resources['cloth']);
        $this->uploadRepository->loadResourcesIntoDatabase($resources['leather']);
    }

    public function updateJournalPricesInAlbionDb(): void
    {
        $journalList = [
            JournalEntity::JOURNAL_WARRIOR,
            JournalEntity::JOURNAL_MAGE,
            JournalEntity::JOURNAL_HUNTER,
        ];
        foreach ($journalList as $journalType) {
            $this->updatePriceFromJournals($journalType);
        }
    }

    public function updateResourcePricesInAlbionDb(): void
    {
        $resourceList = [
            ResourceEntity::RESOURCE_METAL_BAR,
            ResourceEntity::RESOURCE_PLANKS,
            ResourceEntity::RESOURCE_CLOTH,
            ResourceEntity::RESOURCE_LEATHER,
        ];
        foreach ($resourceList as $resource) {
            $this->updatePriceFromResource($resource);
        }
    }

    public function updateItemPricesInAlbionDbByCity(string $city): void
    {
        foreach ($this->itemList as $item) {
            $this->updatePriceFromItem($item, $city);
        }
    }

    public function uploadRefreshedPrices(): void
    {
        $resources = $this->getAdjustedResources();

        $this->uploadRepository->reloadUpdatedPricesResources($resources['metalBar']);
        $this->uploadRepository->reloadUpdatedPricesResources($resources['planks']);
        $this->uploadRepository->reloadUpdatedPricesResources($resources['cloth']);
        $this->uploadRepository->reloadUpdatedPricesResources($resources['leather']);

        $items = $this->getAdjustedItems();

        $this->uploadRepository->reloadUpdatedPricesItems($items['warrior']);
        $this->uploadRepository->reloadUpdatedPricesItems($items['mage']);
        $this->uploadRepository->reloadUpdatedPricesItems($items['hunter']);

        $journals = $this->getAdjustedJournals();

        $this->uploadRepository->reloadUpdatedPricesJournals($journals['warrior']);
        $this->uploadRepository->reloadUpdatedPricesJournals($journals['mage']);
        $this->uploadRepository->reloadUpdatedPricesJournals($journals['hunter']);
    }

    protected function adjustResourceArray(array $resourceArray, string $resourceType): array
    {
        $adjustedResourceArray = [];
        foreach ($resourceArray as $resource) {
            $nameAndTier = TierService::splitIntoTierAndName($resource['item_id']);
            $name = $this->getResourceName($nameAndTier['name']);
            $adjustedResourceArray[] = [
                'tier' => $nameAndTier['tier'],
                'name' => $name,
                'city' => $resource['city'],
                'realName' => $resourceType,
                'sellOrderPrice' => $resource['sell_price_min'],
                'sellOrderPriceDate' => $resource['sell_price_min_date'],
                'buyOrderPrice' => $resource['buy_price_max'],
                'buyOrderPriceDate' => $resource['buy_price_max_date'],
                'bonusCity' => NameDataService::getBonusCityForResource($resourceType),
            ];
        }
        return $adjustedResourceArray;
    }

    private function getAdjustedJournals(): array
    {
        $warriorJournalArray = $this->apiService->getJournals(ApiService::JOURNAL_WARRIOR);
        $warriorJournalArrayAdjusted = $this->adjustJournals($warriorJournalArray);
        $mageJournalArray = $this->apiService->getJournals(ApiService::JOURNAL_MAGE);
        $mageJournalArrayAdjusted = $this->adjustJournals($mageJournalArray);
        $hunterJournalArray = $this->apiService->getJournals(ApiService::JOURNAL_HUNTER);
        $hunterJournalArrayAdjusted = $this->adjustJournals($hunterJournalArray);

        return [
            'warrior' => $warriorJournalArrayAdjusted,
            'mage' => $mageJournalArrayAdjusted,
            'hunter' => $hunterJournalArrayAdjusted,
        ];
    }

    private function getAdjustedResources(): array
    {
        $metalBarArray = $this->apiService->getResource(ResourceEntity::RESOURCE_METAL_BAR);
        $metalBarArrayAdjusted = $this->adjustResourceArray($metalBarArray, ResourceEntity::RESOURCE_METAL_BAR);
        $planksArray = $this->apiService->getResource(ResourceEntity::RESOURCE_PLANKS);
        $planksArrayAdjusted = $this->adjustResourceArray($planksArray, ResourceEntity::RESOURCE_PLANKS);
        $clothArray = $this->apiService->getResource(ResourceEntity::RESOURCE_CLOTH);
        $clothArrayAdjusted = $this->adjustResourceArray($clothArray, ResourceEntity::RESOURCE_CLOTH);
        $leatherArray = $this->apiService->getResource(ResourceEntity::RESOURCE_LEATHER);
        $leatherArrayAdjusted = $this->adjustResourceArray($leatherArray, ResourceEntity::RESOURCE_LEATHER);

        return [
            'metalBar' => $metalBarArrayAdjusted,
            'planks' => $planksArrayAdjusted,
            'cloth' => $clothArrayAdjusted,
            'leather' => $leatherArrayAdjusted,
        ];
    }

    private function getAdjustedItems(): array
    {
        $warriorArray = $this->itemHelper->getWarriorItems();
        $warriorArrayAdjusted = $this->adjustItemsArray($warriorArray, ItemEntity::CLASS_WARRIOR);
        $mageArray = $this->itemHelper->getMageItems();
        $mageArrayAdjusted = $this->adjustItemsArray($mageArray, ItemEntity::CLASS_MAGE);
        $hunterArray = $this->itemHelper->getHunterItems();
        $hunterArrayAdjusted = $this->adjustItemsArray($hunterArray, ItemEntity::CLASS_HUNTER);

        return [
            'warrior' => $warriorArrayAdjusted,
            'mage' => $mageArrayAdjusted,
            'hunter' => $hunterArrayAdjusted,
        ];
    }

    private function adjustJournals(array $journals): array
    {
        $adjustedJournalsArray = [];
        foreach ($journals as $journal) {
            $nameAndTier = TierService::splitIntoTierAndName($journal['item_id']);
            $stats = NameDataService::getStatsJournals($nameAndTier['tier']);
            $split = explode('_', $nameAndTier['name']);
            $adjustedJournalsArray[] = [
                'tier' => $nameAndTier['tier'],
                'name' => implode('_', [$split[0], $split[1]]),
                'city' => $journal['city'],
                'fameToFill' => $stats['fameToFill'],
                'sellOrderPrice' => $journal['sell_price_min'],
                'sellOrderPriceDate' => $journal['sell_price_min_date'],
                'buyOrderPrice' => $journal['buy_price_max'],
                'buyOrderPriceDate' => $journal['buy_price_max_date'],
                'weight' => $stats['weight'],
                'fillStatus' => $split[2],
                'class' => $split[1],
            ];
        }
        return $adjustedJournalsArray;
    }

    private function adjustItemsArray(array $classArray, string $class): array
    {
        $adjustedItemsArray = [];
        foreach ($classArray as $weaponGroupName => $weaponGroup) {
            foreach ($weaponGroup as $weapon) {
                foreach ($weapon as $item) {
                    $nameAndTier = TierService::splitIntoTierAndName($item['item_id']);
                    $stats = NameDataService::getStatsForItem($class, $weaponGroupName, $nameAndTier['name']);
                    $adjustedItemsArray[] = [
                        'tier' => $nameAndTier['tier'],
                        'name' => $nameAndTier['name'],
                        'weaponGroup' => $weaponGroupName,
                        'realName' => $stats['realName'],
                        'class' => $class,
                        'city' => $item['city'],
                        'quality' => $item['quality'],
                        'sellOrderPrice' => $item['sell_price_min'],
                        'sellOrderPriceDate' => $item['sell_price_min_date'],
                        'buyOrderPrice' => $item['buy_price_max'],
                        'buyOrderPriceDate' => $item['buy_price_max_date'],
                        'primaryResource' => $stats['primaryResource'],
                        'primaryResourceAmount' => $stats['primaryResourceAmount'],
                        'secondaryResource' => $stats['secondaryResource'],
                        'secondaryResourceAmount' => $stats['secondaryResourceAmount'],
                        'bonusCity' => $stats['bonusCity'],
                        'fameFactor' => null,
                    ];
                }
            }
        }
        return $adjustedItemsArray;
    }

    private function getResourceName(string $name): string
    {
        if (str_contains($name, '_level1')) {
            $resourceName = str_replace('_level1', '', $name);
        }
        if (str_contains($name, '_level2')) {
            $resourceName = str_replace('_level2', '', $name);
        }
        if (str_contains($name, '_level3')) {
            $resourceName = str_replace('_level3', '', $name);
        }
        return $resourceName ?? $name;
    }

    private function updatePriceFromItem(array $itemData, string $city): void
    {
        $items = $this->apiService->getItems($itemData[0], $city);
        $adjustedItems = $this->adjustItems($items, $itemData);
        $this->uploadRepository->updatePricesFromItem($adjustedItems);
    }

    private function updatePriceFromResource(string $resourceType): void
    {
        $resources = $this->apiService->getResource($resourceType);
        $adjustedResources = $this->adjustResourceArray($resources, $resourceType);
        $this->uploadRepository->updatePricesFromResources($adjustedResources);
    }


    private function updatePriceFromJournals(string $journalType): void
    {
        $journals = $this->apiService->getJournals($journalType);
        $adjustedJournals = $this->adjustJournals($journals);
        $this->uploadRepository->updatePricesFromJournals($adjustedJournals);
    }

    private function adjustItems(array $weaponGroupArray, array $itemData): array
    {
        [$weaponGroupName, $class] = $itemData;
        $adjustedItems = [];
        foreach ($weaponGroupArray as $weapon) {
            foreach ($weapon as $item) {
                $nameAndTier = TierService::splitIntoTierAndName($item['item_id']);
                $stats = NameDataService::getStatsForItem($class, $weaponGroupName, $nameAndTier['name']);
                $adjustedItems[] = [
                    'tier' => $nameAndTier['tier'],
                    'name' => $nameAndTier['name'],
                    'weaponGroup' => $weaponGroupName,
                    'realName' => $stats['realName'],
                    'class' => $class,
                    'city' => $item['city'],
                    'quality' => $item['quality'],
                    'sellOrderPrice' => $item['sell_price_min'],
                    'sellOrderPriceDate' => $item['sell_price_min_date'],
                    'buyOrderPrice' => $item['buy_price_max'],
                    'buyOrderPriceDate' => $item['buy_price_max_date'],
                    'primaryResource' => $stats['primaryResource'],
                    'primaryResourceAmount' => $stats['primaryResourceAmount'],
                    'secondaryResource' => $stats['secondaryResource'],
                    'secondaryResourceAmount' => $stats['secondaryResourceAmount'],
                    'bonusCity' => $stats['bonusCity'],
                    'fameFactor' => null,
                ];
            }
        }
        return $adjustedItems;
    }
}
