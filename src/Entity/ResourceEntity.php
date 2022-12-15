<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity;

class ResourceEntity extends AlbionItemEntity
{
    public const RESOURCE_METAL_BAR = 'metalBar';
    public const RESOURCE_PLANKS = 'planks';
    public const RESOURCE_CLOTH = 'cloth';
    public const RESOURCE_LEATHER = 'leather';
    public const RESOURCE_STONE_BLOCK = 'stoneBLock';


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

    private ?string $bonusCity;
    private ?int $amountInStorage;


    public function __construct(array $resourceData, private bool $raw = false)
    {
        parent::__construct($resourceData);

        $this->bonusCity = $resourceData['bonusCity'];
        $this->amountInStorage = (int)$resourceData['amountInStorage'];
        $this->weight = $this->setWeight($resourceData['tier']);
    }

    public function isRaw(): bool
    {
        return $this->raw;
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
}
