<?php

namespace MZierdt\Albion\Entity;

use DateTimeImmutable;
use DateTimeZone;

class AlbionItemEntity
{
    public const TIER_T2 = '20';
    public const TIER_T3 = '30';
    public const TIER_T4 = '40';
    public const TIER_T4_1 = '41';
    public const TIER_T4_2 = '42';
    public const TIER_T4_3 = '43';
    public const TIER_T4_4 = '44';
    public const TIER_T5 = '50';
    public const TIER_T5_1 = '51';
    public const TIER_T5_2 = '52';
    public const TIER_T5_3 = '53';
    public const TIER_T5_4 = '54';
    public const TIER_T6 = '60';
    public const TIER_T6_1 = '61';
    public const TIER_T6_2 = '62';
    public const TIER_T6_3 = '63';
    public const TIER_T6_4 = '64';
    public const TIER_T7 = '70';
    public const TIER_T7_1 = '71';
    public const TIER_T7_2 = '72';
    public const TIER_T7_3 = '73';
    public const TIER_T7_4 = '74';
    public const TIER_T8 = '80';
    public const TIER_T8_1 = '81';
    public const TIER_T8_2 = '82';
    public const TIER_T8_3 = '83';
    public const TIER_T8_4 = '84';

    protected string $tier;
    protected string $name;
    protected string $city;
    protected int $sellOrderPrice;
    protected int $sellOrderAge;
    protected int $buyOrderPrice;
    protected int $buyOrderAge;
    protected string $realName;
    protected float $weight;
    protected ?string $class;

    public function __construct(array $resourceData)
    {
//        if (!$resourceData['buyOrderPriceDate']|| !$resourceData['sellOrderPriceDate']) {dump($resourceData);}
        $this->tier = $resourceData['tier'];
        $this->name = $resourceData['name'];
        $this->city = $resourceData['city'];
        $this->sellOrderPrice = $resourceData['sellOrderPrice'] ?? 0;
        $this->sellOrderAge = $this->calculateAge($resourceData['sellOrderPriceDate']);
        $this->buyOrderPrice = $resourceData['buyOrderPrice'] ?? 0;
        $this->buyOrderAge = $this->calculateAge($resourceData['buyOrderPriceDate']);
        $this->realName = $resourceData['realName'] ?? '';
        $this->weight = $resourceData['weight'] ?? 0;
        $this->class = $resourceData['class'] ?? '';
    }

    private function calculateAge(?string $dateString): int
    {
        $priceDate = $this->calculateDateTimeImmutable($dateString);

        $now = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', Date('Y-m-d H:i:s'));

//        if(!$priceDate) {
//            dd( $dateString);
//        }

        $dateDiff = date_diff($now, $priceDate);
        return $dateDiff->d * 24 * 60 + $dateDiff->h * 60 + $dateDiff->i;
    }

    private function calculateDateTimeImmutable(?string $dateString): DateTimeImmutable|false
    {
        if ($dateString === null) {
            return DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '1970-03-22 12:12:12');
        }
        $dateString = str_replace('T', ' ', $dateString);
        return DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dateString, new DateTimeZone('Europe/London'));
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

    public function getRealName(): string
    {
        return $this->realName;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function getClass(): string
    {
        return $this->class;
    }
}
