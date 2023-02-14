<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\JournalEntity;
use MZierdt\Albion\Entity\MaterialEntity;
use MZierdt\Albion\Entity\ResourceEntity;

class UploadHelper
{
    public function __construct(private readonly TierService $tierService)
    {
    }

    public function adjustResources(array $resourceData, array $resourceStats, bool $raw = false): array
    {
        $adjustedResourceArray = [];
        foreach ($resourceData as $resource) {
            $nameAndTier = $this->tierService->splitIntoTierAndName($resource['item_id']);
            $name = $this->getResourceName($nameAndTier['name']);
            $resourceEntity = (new ResourceEntity())
                ->setTier((int) $nameAndTier['tier'])
                ->setName($name)
                ->setCity($resource['city'])
                ->calculateSellOrderAge($resource['sell_price_min_date'])
                ->setSellOrderPrice($resource['sell_price_min'])
                ->calculateBuyOrderAge($resource['buy_price_max_date'])
                ->setBuyOrderPrice($resource['buy_price_max'])
                ->setClass($resourceStats['realName'])
                ->setRealName($resourceStats['realName'])
                ->setBonusCity($resourceStats['bonusCity'])
                ->setRaw($raw);

            $adjustedResourceArray[] = $resourceEntity;
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

    public function adjustItems(array $itemData, array $itemStats, bool $bmSellable): array
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
                ->setRealName($itemStats['realName'])
                ->setWeaponGroup($itemStats['weaponGroup'])
                ->setQuality($item['quality'])
                ->setPrimaryResource($itemStats['primaryResource'])
                ->setPrimaryResourceAmount($itemStats['primaryResourceAmount'])
                ->setSecondaryResource($itemStats['secondaryResource'])
                ->setSecondaryResourceAmount($itemStats['secondaryResourceAmount'])
                ->setBonusCity($itemStats['bonusCity'])
                ->setArtifact($itemStats['artifact'])
                ->setBlackMarketSellable($bmSellable)
                ->refreshFame()
                ->refreshItemValue();

            $adjustedItems[] = $itemEntity;
        }
        return $adjustedItems;
    }

    // Input Either MetalBar or MetalBar_level1 Output: MetalBar
    public function getResourceName(string $name): string
    {
        $name = strtolower($name);
        if (str_contains($name, 'level')) {
            return substr($name, 0, -7);
        }
        return $name;
    }

    public function adjustMaterials(array $materials, string $type): array
    {
        $adjustedMaterials = [];
        foreach ($materials as $material) {
            $nameAndTier = $this->tierService->splitIntoTierAndName($material['item_id']);
            $materialEntity = (new MaterialEntity())
                ->setTier((int) $nameAndTier['tier'])
                ->setName($nameAndTier['name'])
                ->setCity($material['city'])
                ->calculateSellOrderAge($material['sell_price_min_date'])
                ->setSellOrderPrice($material['sell_price_min'])
                ->calculateBuyOrderAge($material['buy_price_max_date'])
                ->setBuyOrderPrice($material['buy_price_max'])
                ->setType($type)
                ->setRealName($nameAndTier['name']);
            $adjustedMaterials[] = $materialEntity;
        }
        return $adjustedMaterials;
    }

    public function calculateHeartAmount(int $tier): int
    {
        $tier = (int) ($tier / 10);
        return match ($tier) {
            4, 5 => 1,
            6 => 3,
            7 => 5,
            8 => 10,
            default => throw new \InvalidArgumentException('Wrong Tier in calculateHeartAmount ' . $tier)
        };
    }
}
