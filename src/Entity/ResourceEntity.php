<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity;

use DateTimeImmutable;

class ResourceEntity
{
    private int $tier;
    private string $name;
    private string $city;
    private int $sellOrderPrice;
    private DateTimeImmutable $sellOrderPriceDate;
    private int $buyOrderPrice;
    private DateTimeImmutable $buyOrderPriceDate;

    private const TIER_T2 = 20;
    private const TIER_T3 = 30;
    private const TIER_T4 = 40;
    private const TIER_T4_1 = 41;
    private const TIER_T4_2 = 42;
    private const TIER_T4_3 = 43;
    private const TIER_T5 = 50;
    private const TIER_T5_1 = 51;
    private const TIER_T5_2 = 52;
    private const TIER_T5_3 = 53;
    private const TIER_T6 = 60;
    private const TIER_T6_1 = 61;
    private const TIER_T6_2 = 62;
    private const TIER_T6_3 = 63;
    private const TIER_T7 = 70;
    private const TIER_T7_1 = 71;
    private const TIER_T7_2 = 72;
    private const TIER_T7_3 = 73;
    private const TIER_T8 = 80;
    private const TIER_T8_1 = 81;
    private const TIER_T8_2 = 82;
    private const TIER_T8_3 = 83;

    public function __construct(array $resourceData)
    {
        $split = $this->splitIdIntoNameAndTier($resourceData['itemID']);

        $this->tier = $split['tier'];
        $this->name = $split['name'];
        $this->city = $resourceData['city'];
        $this->sellOrderPrice = $resourceData['sellOrderPrice'];
        $this->sellOrderPriceDate = new DateTimeImmutable($resourceData['sellOrderPriceDate']);
        $this->buyOrderPrice = $resourceData['buyOrderPrice'];
        $this->buyOrderPriceDate = new DateTimeImmutable($resourceData['buyOrderPriceDate']);
    }

    private function splitIdIntoNameAndTier(string $itemId): array
    {
        $itemIdArray = explode('_', $itemId);

        if ($itemIdArray[0] === 'T2' || $itemIdArray[0] === 'T3') {
            return [
                'tier' => $this->tierConverter($itemIdArray[0]),
                'name' => $itemIdArray[1],
            ];
        }
        $preTier = array_shift($itemIdArray);
        $itemName = implode('_', $itemIdArray);

        if (!str_contains($itemName,'@')) {
            return [
                'tier' => $this->tierConverter($preTier),
                'name' => $itemName
            ];
        }

        $explodedNameEnchantment = explode('@', $itemName);

        return [
            'tier' => $this->tierConverter($preTier. $explodedNameEnchantment[1]),
            'name' => $explodedNameEnchantment[0]
        ];
    }

    private function tierConverter(string $tierString): int
    {
        return match ($tierString) {
            'T2' => self::TIER_T2,
            'T3' => self::TIER_T3,
            'T4' => self::TIER_T4,
            'T5' => self::TIER_T5,
            'T6' => self::TIER_T6,
            'T7' => self::TIER_T7,
            'T8' => self::TIER_T8,
        };
    }
}
