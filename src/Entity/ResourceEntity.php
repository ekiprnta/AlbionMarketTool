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

    private string $tier;
    private string $name;
    private string $city;
    private int $sellOrderPrice;
    private DateTimeImmutable $sellOrderPriceDate;
    private int $buyOrderPrice;
    private DateTimeImmutable $buyOrderPriceDate;
    private string $bonusCity;
    private int $amountInStorage;

    public function __construct(array $resourceData)
    {
        $split = $this->splitIdIntoNameAndTier($resourceData['itemId']);
        $sellOrderPriceDate = $this->getDateTimeImmutable($resourceData['sellOrderPriceDate']);
        $buyOrderPriceDate = $this->getDateTimeImmutable($resourceData['buyOrderPriceDate']);

        $this->tier = $split['tier'];
        $this->name = $split['name'];
        $this->city = $resourceData['city'];
        $this->sellOrderPrice = (int) $resourceData['sellOrderPrice'];
        $this->sellOrderPriceDate = $sellOrderPriceDate;
        $this->buyOrderPrice = (int) $resourceData['buyOrderPrice'];
        $this->buyOrderPriceDate = $buyOrderPriceDate;
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

        if (! str_contains($itemName, '@')) {
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
}
