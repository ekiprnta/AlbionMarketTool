<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity;

use DateTimeImmutable;
use DateTimeZone;
use MZierdt\Albion\factories\ResourceEntityFactory;
use MZierdt\Albion\Service\TimeHelper;

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
    private const TIER_T4_1 = '41';
    private const TIER_T4_2 = '42';
    private const TIER_T4_3 = '43';
    private const TIER_T5 = '5';
    private const TIER_T5_1 = '51';
    private const TIER_T5_2 = '52';
    private const TIER_T5_3 = '53';
    private const TIER_T6 = '6';
    private const TIER_T6_1 = '61';
    private const TIER_T6_2 = '62';
    private const TIER_T6_3 = '63';
    private const TIER_T7 = '7';
    private const TIER_T7_1 = '71';
    private const TIER_T7_2 = '72';
    private const TIER_T7_3 = '73';
    private const TIER_T8 = '8';
    private const TIER_T8_1 = '81';
    private const TIER_T8_2 = '82';
    private const TIER_T8_3 = '83';

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
    private ?string $realName;
    private int $sellOrderPrice;
    private int $sellOrderAge;
    private int $buyOrderPrice;
    private int $buyOrderAge;
    private ?string $bonusCity;
    private ?int $amountInStorage;
    private float $weight;

    public function __construct(array $resourceData)
    {
        $weight = $this->setWeight($resourceData['tier']);

        $this->tier = $resourceData['tier'];
        $this->name = $resourceData['name'];
        $this->city = $resourceData['city'];
        $this->realName = $resourceData['realName'];
        $this->sellOrderPrice = (int) $resourceData['sellOrderPrice'];
        $this->sellOrderAge = TimeHelper::calculateAge($resourceData['sellOrderPriceDate']);
        $this->buyOrderPrice = (int) $resourceData['buyOrderPrice'];
        $this->buyOrderAge = TimeHelper::calculateAge($resourceData['buyOrderPriceDate']);
        $this->bonusCity = $resourceData['bonusCity'];
        $this->amountInStorage = (int)$resourceData['amountInStorage'];
        $this->weight = $weight;
    }

    public function setAmountInStorage(?int $amountInStorage): void
    {
        $this->amountInStorage = $amountInStorage;
    }

    public function getBonusCity(): mixed
    {
        return $this->bonusCity;
    }

    public function getAmountInStorage(): ?int
    {
        return $this->amountInStorage;
    }

    public function getWeight(): float
    {
        return $this->weight;
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
        return $this->sellOrderPrice ?? 0;
    }

    public function getSellOrderAge(): int
    {
        return $this->sellOrderAge;
    }

    public function getBuyOrderPrice(): int
    {
        return $this->buyOrderPrice;
    }

    public function getBuyOrderAge(): int
    {
        return $this->buyOrderAge;
    }

    private function setWeight(string $tier): float
    {
        return match ($tier) {
            self::TIER_T2 => self::T20_WEIGHT_FACTOR,
            self::TIER_T3 => self::T30_WEIGHT_FACTOR,
            self::TIER_T4 => self::T40_WEIGHT_FACTOR,
            self::TIER_T4_1 => self::T41_WEIGHT_FACTOR,
            self::TIER_T4_2 => self::T42_WEIGHT_FACTOR,
            self::TIER_T4_3 => self::T43_WEIGHT_FACTOR,
            self::TIER_T5 => self::T50_WEIGHT_FACTOR,
            self::TIER_T5_1 => self::T51_WEIGHT_FACTOR,
            self::TIER_T5_2 => self::T52_WEIGHT_FACTOR,
            self::TIER_T5_3 => self::T53_WEIGHT_FACTOR,
            self::TIER_T6 => self::T60_WEIGHT_FACTOR,
            self::TIER_T6_1 => self::T61_WEIGHT_FACTOR,
            self::TIER_T6_2 => self::T62_WEIGHT_FACTOR,
            self::TIER_T6_3 => self::T63_WEIGHT_FACTOR,
            self::TIER_T7 => self::T70_WEIGHT_FACTOR,
            self::TIER_T7_1 => self::T71_WEIGHT_FACTOR,
            self::TIER_T7_2 => self::T72_WEIGHT_FACTOR,
            self::TIER_T7_3 => self::T73_WEIGHT_FACTOR,
            self::TIER_T8 => self::T80_WEIGHT_FACTOR,
            self::TIER_T8_1 => self::T81_WEIGHT_FACTOR,
            self::TIER_T8_2 => self::T82_WEIGHT_FACTOR,
            self::TIER_T8_3 => self::T83_WEIGHT_FACTOR,
            '0' => 0.1,
            default => throw new \InvalidArgumentException('wrong tier in Resource Entity: ' . $tier),
        };
    }

    public function getRealName(): string
    {
        return $this->realName;
    }
}
