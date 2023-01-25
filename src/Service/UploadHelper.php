<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

class UploadHelper
{
    public function __construct(private readonly TierService $tierService)
    {
    }

    public function adjustResourceArray(array $resourceData, array $resourceStats): array
    {
        $adjustedResourceArray = [];
        foreach ($resourceData as $resource) {
            $nameAndTier = $this->tierService->splitIntoTierAndName($resource['item_id']);
            $name = $this->getResourceName($nameAndTier['name']);
            $adjustedResourceArray[] = [
                'tier' => $nameAndTier['tier'],
                'name' => $name,
                'city' => $resource['city'],
                'realName' => $resourceStats['realName'],
                'sellOrderPrice' => $resource['sell_price_min'],
                'sellOrderPriceDate' => $resource['sell_price_min_date'],
                'buyOrderPrice' => $resource['buy_price_max'],
                'buyOrderPriceDate' => $resource['buy_price_max_date'],
                'bonusCity' => $resourceStats['bonusCity'],
            ];
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
            $adjustedJournalsArray[] = [
                'tier' => $nameAndTier['tier'],
                'name' => $nameAndTier['name'],
                'city' => $journal['city'],
                'fameToFill' => $stats['fameToFill'],
                'sellOrderPrice' => $journal['sell_price_min'],
                'sellOrderPriceDate' => $journal['sell_price_min_date'],
                'buyOrderPrice' => $journal['buy_price_max'],
                'buyOrderPriceDate' => $journal['buy_price_max_date'],
                'weight' => $stats['weight'],
                'fillStatus' => $split['fillStatus'],
                'class' => $split['class'],
            ];
        }
        return $adjustedJournalsArray;
    }

    public function adjustItems(array $itemData, array $itemStats): array
    {
        $adjustedItems = [];
        foreach ($itemData as $item) {
            $nameAndTier = $this->tierService->splitIntoTierAndName($item['item_id']);
            $adjustedItems[] = [
                'tier' => $nameAndTier['tier'],
                'name' => $nameAndTier['name'],
                'weaponGroup' => $itemStats['weaponGroup'],
                'realName' => $itemStats['realName'],
                'class' => $itemStats['class'],
                'city' => $item['city'],
                'quality' => $item['quality'],
                'sellOrderPrice' => $item['sell_price_min'],
                'sellOrderPriceDate' => $item['sell_price_min_date'],
                'buyOrderPrice' => $item['buy_price_max'],
                'buyOrderPriceDate' => $item['buy_price_max_date'],
                'primaryResource' => $itemStats['primaryResource'],
                'primaryResourceAmount' => $itemStats['primaryResourceAmount'],
                'secondaryResource' => $itemStats['secondaryResource'],
                'secondaryResourceAmount' => $itemStats['secondaryResourceAmount'],
                'bonusCity' => $itemStats['bonusCity'],
                'fameFactor' => null,
            ];
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
