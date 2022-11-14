<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\JournalEntity;
use MZierdt\Albion\Entity\ResourceEntity;
use MZierdt\Albion\repositories\UploadRepository;
use Symfony\Component\Console\Output\OutputInterface;

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
    ) {
    }


    public function updateJournalPricesInAlbionDb(OutputInterface $output): void
    {
        $journalList = [
            JournalEntity::JOURNAL_WARRIOR,
            JournalEntity::JOURNAL_MAGE,
            JournalEntity::JOURNAL_HUNTER,
        ];

        $progressBar = ProgressBarService::getProgressBar($output, 210);
        $progressBar->setMessage('Getting Journals...');
        foreach ($journalList as $journalType) {
            $journals = $this->apiService->getJournals($journalType);
            $adjustedJournals = $this->adjustJournals($journals);
            $this->uploadRepository->updatePricesFromJournals($adjustedJournals, $progressBar);
        }
    }


    public function updateResourcePricesInAlbionDb(OutputInterface $output): void
    {
        $resourceList = [
            ResourceEntity::RESOURCE_METAL_BAR,
            ResourceEntity::RESOURCE_PLANKS,
            ResourceEntity::RESOURCE_CLOTH,
            ResourceEntity::RESOURCE_LEATHER,
        ];
        $progressBar = ProgressBarService::getProgressBar($output, 440);
        $progressBar->setMessage('Getting Resources...');
        foreach ($resourceList as $resource) {
            $resources = $this->apiService->getResource($resource);
            $adjustedResources = $this->adjustResourceArray($resources, $resource);
            $this->uploadRepository->updatePricesFromResources($adjustedResources, $progressBar);
        }
    }


    public function updateItemPricesInAlbionDb(OutputInterface $output): void
    {
        $progressBar = ProgressBarService::getProgressBar($output, count($this->itemList));

        foreach ($this->itemList as $item) {
            $items = $this->apiService->getItems($item[0]);
            $progressBar->setMessage('Get Item:' . $item[0]);
            $progressBar->advance();
            $progressBar->display();
            $adjustedItems = $this->adjustItems($items, $item);

            $this->uploadRepository->updatePricesFromItem($adjustedItems);
        }
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
