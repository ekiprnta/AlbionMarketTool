<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity;

use DateTimeImmutable;
use DateTimeZone;

class ItemEntity
{
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

    private const T20_WEIGHT_FACTOR = 0.1;
    private const T30_WEIGHT_FACTOR = 0.14;
    private const T40_WEIGHT_FACTOR = 0.21;
    private const T41_WEIGHT_FACTOR = 0.21;
    private const T42_WEIGHT_FACTOR = 0.21;
    private const T43_WEIGHT_FACTOR = 0.21;
    private const T50_WEIGHT_FACTOR = 0.316;
    private const T51_WEIGHT_FACTOR = 0.316;
    private const T52_WEIGHT_FACTOR = 0.316;
    private const T53_WEIGHT_FACTOR = 0.316;
    private const T60_WEIGHT_FACTOR = 0.475;
    private const T61_WEIGHT_FACTOR = 0.475;
    private const T62_WEIGHT_FACTOR = 0.475;
    private const T63_WEIGHT_FACTOR = 0.475;
    private const T70_WEIGHT_FACTOR = 0.7125;
    private const T71_WEIGHT_FACTOR = 0.7125;
    private const T72_WEIGHT_FACTOR = 0.7125;
    private const T73_WEIGHT_FACTOR = 0.7125;
    private const T80_WEIGHT_FACTOR = 1.056;
    private const T81_WEIGHT_FACTOR = 1.056;
    private const T82_WEIGHT_FACTOR = 1.056;
    private const T83_WEIGHT_FACTOR = 1.056;

    private const T20_FACTOR_FAME = 1;
    private const T30_FACTOR_FAME = 5;
    private const T40_FACTOR_FAME = 15;
    private const T41_FACTOR_FAME = 30;
    private const T42_FACTOR_FAME = 60;
    private const T43_FACTOR_FAME = 120;
    private const T50_FACTOR_FAME = 60;
    private const T51_FACTOR_FAME = 120;
    private const T52_FACTOR_FAME = 240;
    private const T53_FACTOR_FAME = 480;
    private const T60_FACTOR_FAME = 180;
    private const T61_FACTOR_FAME = 360;
    private const T62_FACTOR_FAME = 720;
    private const T63_FACTOR_FAME = 1440;
    private const T70_FACTOR_FAME = 430;
    private const T71_FACTOR_FAME = 860;
    private const T72_FACTOR_FAME = 1720;
    private const T73_FACTOR_FAME = 3440;
    private const T80_FACTOR_FAME = 930;
    private const T81_FACTOR_FAME = 1860;
    private const T82_FACTOR_FAME = 3720;
    private const T83_FACTOR_FAME = 7440;

    public const CLASS_WARRIOR = 'warrior';
    public const CLASS_MAGE = 'mage';
    public const CLASS_HUNTER = 'hunter';

    private string $tier;
    private string $name;
    private string $weaponGroup;
    private string $class;
    private string $city;
    private int $quality;
    private int $sellOrderPrice;
    private DateTimeImmutable $sellOrderPriceDate;
    private int $buyOrderPrice;
    private DateTimeImmutable $buyOrderPriceDate;
    private string $primaryResource;
    private int $primaryResourceAmount;
    private string $secondaryResource;
    private int $secondaryResourceAmount;
    private string $bonusCity;
    private float $fameFactor;
    private ?int $amountInStorage;
    private float $weight;


    public function __construct(array $itemData)
    {
        $sellOrderPriceDate = $this->getDateTimeImmutable($itemData['sellOrderPriceDate']);
        $buyOrderPriceDate = $this->getDateTimeImmutable($itemData['buyOrderPriceDate']);
        $weight = $this->setWeight($itemData);

        $this->tier = $itemData['tier'];
        $this->name = $itemData['name'];
        $this->weaponGroup = $itemData['weaponGroup'];
        $this->class = $itemData['class'];
        $this->city = $itemData['city'];
        $this->quality = (int) $itemData['quality'];
        $this->sellOrderPrice = (int) $itemData['sellOrderPrice'];
        $this->sellOrderPriceDate = $sellOrderPriceDate;
        $this->buyOrderPrice = (int) $itemData['buyOrderPrice'];
        $this->buyOrderPriceDate = $buyOrderPriceDate;
        $this->primaryResource = $itemData['primaryResource'];
        $this->primaryResourceAmount = (int) $itemData['primaryResourceAmount'];
        $this->secondaryResource = $itemData['secondaryResource'];
        $this->secondaryResourceAmount = (int) $itemData['secondaryResourceAmount'];
        $this->bonusCity = $itemData['bonusCity'];
        $this->fameFactor = $this->setFameFactor();
        $this->amountInStorage = $itemData['amountInStorage'];
        $this->weight = $weight;
    }

    private function setFameFactor(): float
    {
        return match ($this->tier) {
            self::TIER_T2 => self::T20_FACTOR_FAME,
            self::TIER_T3 => self::T30_FACTOR_FAME,
            self::TIER_T4 => self::T40_FACTOR_FAME,
            self::TIER_T4_1 => self::T41_FACTOR_FAME,
            self::TIER_T4_2 => self::T42_FACTOR_FAME,
            self::TIER_T4_3 => self::T43_FACTOR_FAME,
            self::TIER_T5 => self::T50_FACTOR_FAME,
            self::TIER_T5_1 => self::T51_FACTOR_FAME,
            self::TIER_T5_2 => self::T52_FACTOR_FAME,
            self::TIER_T5_3 => self::T53_FACTOR_FAME,
            self::TIER_T6 => self::T60_FACTOR_FAME,
            self::TIER_T6_1 => self::T61_FACTOR_FAME,
            self::TIER_T6_2 => self::T62_FACTOR_FAME,
            self::TIER_T6_3 => self::T63_FACTOR_FAME,
            self::TIER_T7 => self::T70_FACTOR_FAME,
            self::TIER_T7_1 => self::T71_FACTOR_FAME,
            self::TIER_T7_2 => self::T72_FACTOR_FAME,
            self::TIER_T7_3 => self::T73_FACTOR_FAME,
            self::TIER_T8 => self::T80_FACTOR_FAME,
            self::TIER_T8_1 => self::T81_FACTOR_FAME,
            self::TIER_T8_2 => self::T82_FACTOR_FAME,
            self::TIER_T8_3 => self::T83_FACTOR_FAME,
            default => throw new \InvalidArgumentException('wrong Factor in Item Entity')
        };
    }

    public function setAmountInStorage(mixed $amountInStorage): void
    {
        $this->amountInStorage = $amountInStorage;
    }


    public function getWeaponGroup(): mixed
    {
        return $this->weaponGroup;
    }

    public function getClass(): mixed
    {
        return $this->class;
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

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getQuality(): int
    {
        return $this->quality;
    }

    public function getSellOrderPrice(): int
    {
        return $this->sellOrderPrice;
    }

    public function getSellOrderPriceDate(): DateTimeImmutable|bool
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

    public function getBuyOrderPriceDate(): DateTimeImmutable|bool
    {
        return $this->buyOrderPriceDate;
    }

    public function getBuyOrderPriceDateString(): string
    {
        return $this->buyOrderPriceDate->format('Y-m-d H:i:s');
    }

    public function getPrimaryResource(): string
    {
        return $this->primaryResource;
    }

    public function getPrimaryResourceAmount(): int
    {
        return $this->primaryResourceAmount;
    }

    public function getSecondaryResource(): string
    {
        return $this->secondaryResource;
    }

    public function getSecondaryResourceAmount(): int
    {
        return $this->secondaryResourceAmount;
    }

    public function getBonusCity(): string
    {
        return $this->bonusCity;
    }

    public function getFameFactor(): float
    {
        return $this->fameFactor;
    }

    private function splitIdIntoNameAndTier(string $itemId): array
    {
        $itemId = strtolower($itemId);
        $itemIdArray = explode('_', $itemId);

        if ($itemIdArray[0] === 't2' || $itemIdArray[0] === 't3') {
            return [
                'tier' => $this->tierConverter(array_shift($itemIdArray)),
                'name' => implode('_', $itemIdArray),
            ];
        }
        $preTier = array_shift($itemIdArray);
        $itemName = implode('_', $itemIdArray);

        if (! str_contains($itemName, '@')) {
            return [
                'tier' => $this->tierConverter($preTier),
                'name' => $itemName,
            ];
        }

        $explodedNameEnchantment = explode('@', $itemName);

        return [
            'tier' => $this->tierConverter($preTier . $explodedNameEnchantment[1]),
            'name' => $explodedNameEnchantment[0],
        ];
    }

    private function tierConverter(string $tierString): string
    {
        return match ($tierString) {
            't2' => self::TIER_T2,
            't3' => self::TIER_T3,
            't4' => self::TIER_T4,
            't41' => self::TIER_T4_1,
            't42' => self::TIER_T4_2,
            't43' => self::TIER_T4_3,
            't5' => self::TIER_T5,
            't51' => self::TIER_T5_1,
            't52' => self::TIER_T5_2,
            't53' => self::TIER_T5_3,
            't6' => self::TIER_T6,
            't61' => self::TIER_T6_1,
            't62' => self::TIER_T6_2,
            't63' => self::TIER_T6_3,
            't7' => self::TIER_T7,
            't71' => self::TIER_T7_1,
            't72' => self::TIER_T7_2,
            't73' => self::TIER_T7_3,
            't8' => self::TIER_T8,
            't81' => self::TIER_T8_1,
            't82' => self::TIER_T8_2,
            't83' => self::TIER_T8_3,
            default => throw new \InvalidArgumentException('wrong tier in Item Entity')
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

    private function setWeight(array $itemData): float
    {
        $weightFactor = match ($itemData['tier'])
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
            default => throw new \InvalidArgumentException('wrong tier in Item Entity')
        };

        return ($itemData['primaryResourceAmount'] + $itemData['secondaryResourceAmount']) * $weightFactor;
    }
}
