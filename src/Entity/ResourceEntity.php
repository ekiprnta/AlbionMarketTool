<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity;

use DateTimeImmutable;
use DateTimeZone;

class ResourceEntity
{
    public const RESOURCE_METAL_BAR = 'metalBar';
    public const RESOURCE_PLANKS = 'planks';
    public const RESOURCE_CLOTH = 'cloth';
    public const RESOURCE_LEATHER = 'leather';
    public const RESOURCE_STONE_BLOCK = 'stoneBLock';

    private const TIER_T2 = '2';
    private const TIER_T3 = '3';
    private const TIER_T4 = '4';
    private const TIER_T4_1 = '4.1';
    private const TIER_T4_2 = '4.2';
    private const TIER_T4_3 = '4.3';
    private const TIER_T5 = '5';
    private const TIER_T5_1 = '5.1';
    private const TIER_T5_2 = '5.2';
    private const TIER_T5_3 = '5.3';
    private const TIER_T6 = '6';
    private const TIER_T6_1 = '6.1';
    private const TIER_T6_2 = '6.2';
    private const TIER_T6_3 = '6.3';
    private const TIER_T7 = '7';
    private const TIER_T7_1 = '7.1';
    private const TIER_T7_2 = '7.2';
    private const TIER_T7_3 = '7.3';
    private const TIER_T8 = '8';
    private const TIER_T8_1 = '8.1';
    private const TIER_T8_2 = '8.2';
    private const TIER_T8_3 = '8.3';

    private const T20_WEIGHT_FACTOR = 0.23;
    private const T30_WEIGHT_FACTOR = 0.34;
    private const T40_WEIGHT_FACTOR = 0.51;
    private const T41_WEIGHT_FACTOR = 0.51;
    private const T42_WEIGHT_FACTOR = 0.51;
    private const T43_WEIGHT_FACTOR = 0.51;
    private const T50_WEIGHT_FACTOR = 0.76;
    private const T51_WEIGHT_FACTOR = 0.76;
    private const T52_WEIGHT_FACTOR = 0.76;
    private const T53_WEIGHT_FACTOR = 0.76;
    private const T60_WEIGHT_FACTOR = 1.14;
    private const T61_WEIGHT_FACTOR = 1.14;
    private const T62_WEIGHT_FACTOR = 1.14;
    private const T63_WEIGHT_FACTOR = 1.14;
    private const T70_WEIGHT_FACTOR = 1.71;
    private const T71_WEIGHT_FACTOR = 1.71;
    private const T72_WEIGHT_FACTOR = 1.71;
    private const T73_WEIGHT_FACTOR = 1.71;
    private const T80_WEIGHT_FACTOR = 2.56;
    private const T81_WEIGHT_FACTOR = 2.56;
    private const T82_WEIGHT_FACTOR = 2.56;
    private const T83_WEIGHT_FACTOR = 2.56;

    private string $tier;
    private string $name;
    private string $city;
    private int $sellOrderPrice;
    private DateTimeImmutable $sellOrderPriceDate;
    private int $buyOrderPrice;
    private DateTimeImmutable $buyOrderPriceDate;
    private string $bonusCity;
    private ?int $amountInStorage;
    private float $weight;

    public function __construct(array $resourceData)
    {
        $sellOrderPriceDate = $this->getDateTimeImmutable($resourceData['sellOrderPriceDate']);
        $buyOrderPriceDate = $this->getDateTimeImmutable($resourceData['buyOrderPriceDate']);
        $weight = $this->setWeight($resourceData);

        $this->tier = $resourceData['tier'];
        $this->name = $resourceData['name'];
        $this->city = $resourceData['city'];
        $this->sellOrderPrice = (int)$resourceData['sellOrderPrice'];
        $this->sellOrderPriceDate = $sellOrderPriceDate;
        $this->buyOrderPrice = (int)$resourceData['buyOrderPrice'];
        $this->buyOrderPriceDate = $buyOrderPriceDate;
        $this->bonusCity = $resourceData['bonusCity'];
        $this->amountInStorage = $resourceData['amountInStorage'];
        $this->weight = $weight;
    }

    public function setAmountInStorage(mixed $amountInStorage): void
    {
        $this->amountInStorage = $amountInStorage;
    }

    public function getBonusCity(): mixed
    {
        return $this->bonusCity;
    }

    public function getAmountInStorage(): mixed
    {
        return $this->amountInStorage;
    }

    public function getTier(): string
    {
        return $this->tier;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getSellOrderPrice(): int
    {
        return $this->sellOrderPrice;
    }

    public function getSellOrderPriceDate(): DateTimeImmutable
    {
        return $this->sellOrderPriceDate;
    }

    public function getSellOrderPriceDateString(): string
    {
        return $this->sellOrderPriceDate->format('Y-m-d H:i:s');
    }

    public function getBuyOrderPrice(): int
    {
        return $this->buyOrderPrice;
    }

    public function getBuyOrderPriceDate(): DateTimeImmutable
    {
        return $this->buyOrderPriceDate;
    }

    public function getBuyOrderPriceDateString(): string
    {
        return $this->buyOrderPriceDate->format('Y-m-d H:i:s');
    }

    private function splitIdIntoNameAndTier(string $itemId): array
    {
        $itemId = strtolower($itemId);
        $itemIdArray = explode('_', $itemId);

        if ($itemIdArray[0] === 'T2' || $itemIdArray[0] === 'T3') {
            return [
                'tier' => $this->tierConverter($itemIdArray[0]),
                'name' => $itemIdArray[1],
            ];
        }
        $preTier = array_shift($itemIdArray);
        $itemName = implode('_', $itemIdArray);

        if (!str_contains($itemName, '@')) {
            return [
                'tier' => $this->tierConverter($preTier),
                'name' => $itemName,
            ];
        }

        $explodedNameEnchantment = explode('@', $itemName);
        $explodedName = explode('_', $explodedNameEnchantment[0]);

        return [
            'tier' => $this->tierConverter($preTier . $explodedNameEnchantment[1]),
            'name' => $explodedName[0],
        ];
    }

    private function tierConverter(string $tierString): string
    {
        return match ($tierString) {
            self::TIER_T2 => self::TIER_T2,
            self::TIER_T3 => self::TIER_T3,
            self::TIER_T4 => self::TIER_T4,
            self::TIER_T4_1 => self::TIER_T4_1,
            self::TIER_T4_2 => self::TIER_T4_2,
            self::TIER_T4_3 => self::TIER_T4_3,
            self::TIER_T5 => self::TIER_T5,
            self::TIER_T5_1 => self::TIER_T5_1,
            self::TIER_T5_2 => self::TIER_T5_2,
            self::TIER_T5_3 => self::TIER_T5_3,
            self::TIER_T6 => self::TIER_T6,
            self::TIER_T6_1 => self::TIER_T6_1,
            self::TIER_T6_2 => self::TIER_T6_2,
            self::TIER_T6_3 => self::TIER_T6_3,
            self::TIER_T7 => self::TIER_T7,
            self::TIER_T7_1 => self::TIER_T7_1,
            self::TIER_T7_2 => self::TIER_T7_2,
            self::TIER_T7_3 => self::TIER_T7_3,
            self::TIER_T8 => self::TIER_T8,
            self::TIER_T8_1 => self::TIER_T8_1,
            self::TIER_T8_2 => self::TIER_T8_2,
            self::TIER_T8_3 => self::TIER_T8_3,
            default => throw new \InvalidArgumentException('wrong Tier in Resource Entity')
        };
    }

    private function getDateTimeImmutable(mixed $sellOrderPriceDate): DateTimeImmutable|bool
    {
        $sellOrderPriceDate = str_replace('T', ' ', $sellOrderPriceDate);
        return DateTimeImmutable::createFromFormat(
            'Y-m-d H:i:s',
            $sellOrderPriceDate,
            new DateTimeZone('Europe/London')
        );
    }

    private function setWeight(array $resourceData)
    {
        $weightFactor = match ($resourceData['tier'])
        {
            't2' => self::T20_WEIGHT_FACTOR,
            't3' => self::T30_WEIGHT_FACTOR,
            't4' => self::T40_WEIGHT_FACTOR,
            't41' => self::T41_WEIGHT_FACTOR,
            't42' => self::T42_WEIGHT_FACTOR,
            't43' => self::T43_WEIGHT_FACTOR,
            't5' => self::T50_WEIGHT_FACTOR,
            't51' => self::T51_WEIGHT_FACTOR,
            't52' => self::T52_WEIGHT_FACTOR,
            't53' => self::T53_WEIGHT_FACTOR,
            't6' => self::T60_WEIGHT_FACTOR,
            't61' => self::T61_WEIGHT_FACTOR,
            't62' => self::T62_WEIGHT_FACTOR,
            't63' => self::T63_WEIGHT_FACTOR,
            't7' => self::T70_WEIGHT_FACTOR,
            't71' => self::T71_WEIGHT_FACTOR,
            't72' => self::T72_WEIGHT_FACTOR,
            't73' => self::T73_WEIGHT_FACTOR,
            't8' => self::T80_WEIGHT_FACTOR,
            't81' => self::T81_WEIGHT_FACTOR,
            't82' => self::T82_WEIGHT_FACTOR,
            't83' => self::T83_WEIGHT_FACTOR,
            default => throw new \InvalidArgumentException('wrong tier in Resource Entity')
        };

        return ($resourceData['primaryResourceAmount'] + $resourceData['secondaryResourceAmount']) * $weightFactor;
    }
}
