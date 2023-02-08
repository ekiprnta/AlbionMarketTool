<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\JournalEntity;
use MZierdt\Albion\Entity\ResourceEntity;

class UploadHelper
{
    public function __construct(private readonly TierService $tierService)
    {
    }

    public function adjustResourceArray(array $resourceData, array $resourceStats, bool $raw = false): array
    {
        $adjustedResourceArray = [];
        foreach ($resourceData as $resource) {
            $nameAndTier = $this->tierService->splitIntoTierAndName($resource['item_id']);
            $name = $this->getResourceName($nameAndTier['name']);
            $adjustedResourceArray[] = new ResourceEntity([
                'tier' => $nameAndTier['tier'],
                'name' => $name,
                'city' => $resource['city'],
                'realName' => $resourceStats['realName'],
                'sellOrderPrice' => $resource['sell_price_min'],
                'sellOrderPriceDate' => $resource['sell_price_min_date'],
                'buyOrderPrice' => $resource['buy_price_max'],
                'buyOrderPriceDate' => $resource['buy_price_max_date'],
                'bonusCity' => $resourceStats['bonusCity'],
            ], $raw);
        }
        return $adjustedResourceArray;
    }

    public function adjustJournals(array $journalData, array $journalStats): array
    {
        $adjustedJournalsArray = [];
        foreach ($journalData as $journal) {
            $nameAndTier = $this->tierService->splitIntoTierAndName($journal['item_id']);
            $stats = $journalStats[$nameAndTier['tier']];
            $split = $this->tierService->journalSplitter($nameAndTier['name']);
            $journalEntity = (new JournalEntity())
                ->setTier((int) $nameAndTier['tier'])
                ->setName($nameAndTier['name'])
                ->setCity($journal['city'])
                ->calculateSellOrderAge($journal['sell_price_min_date'])
                ->setSellOrderPrice($journal['sell_price_min'])
                ->calculateBuyOrderAge($journal['buy_price_max_date'])
                ->setBuyOrderPrice($journal['buy_price_max'])
                ->setClass($split['class'])
                ->setRealName($split['class'])
                ->setFameToFill($stats['fameToFill'])
                ->setFillStatus($split['fillStatus']);
            $adjustedJournalsArray[] = $journalEntity;
        }
        return $adjustedJournalsArray;
    }

    public function adjustItems(array $itemData, array $itemStats): array
    {
        $adjustedItems = [];
        foreach ($itemData as $item) {
            $nameAndTier = $this->tierService->splitIntoTierAndName($item['item_id']);
            $itemEntity = (new ItemEntity())
                ->setTier((int) $nameAndTier['tier'])
                ->setName($nameAndTier['name'])
                ->setCity($item['city'])
                ->calculateSellOrderAge($item['sell_price_min_date'])
                ->setSellOrderPrice($item['sell_price_min'])
                ->calculateBuyOrderAge($item['buy_price_max_date'])
                ->setBuyOrderPrice($item['buy_price_max'])
                ->setClass($itemStats['class'])
                ->setRealName($itemStats['class'])
                ->setWeaponGroup($itemStats['weaponGroup'])
                ->setQuality($item['quality'])
                ->setPrimaryResource($itemStats['primaryResource'])
                ->setPrimaryResourceAmount($itemStats['primaryResourceAmount'])
                ->setSecondaryResource($itemStats['secondaryResource'])
                ->setSecondaryResourceAmount($itemStats['secondaryResourceAmount'])
                ->setBonusCity($itemStats['bonusCity'])
                ->refreshFame()
                ->refreshItemValue();

            $adjustedItems[] = $itemEntity;
        }
        return $adjustedItems;
    }

    // Input Either MetalBar or MetalBar_level1 Output: MetalBar
    public function getResourceName(string $name): string
    {
        if (str_contains($name, 'level')) {
            return substr($name, 0, -7);
        }
        return $name;
    }
}
