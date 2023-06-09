<?php

namespace MZierdt\Albion\Entity;

use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\MappedSuperclass;

#[MappedSuperclass]
class AlbionItemEntity
{
    final public const TIER_T2 = 20;
    final public const TIER_T3 = 30;
    final public const TIER_T4 = 40;
    final public const TIER_T4_1 = 41;
    final public const TIER_T4_2 = 42;
    final public const TIER_T4_3 = 43;
    final public const TIER_T4_4 = 44;
    final public const TIER_T5 = 50;
    final public const TIER_T5_1 = 51;
    final public const TIER_T5_2 = 52;
    final public const TIER_T5_3 = 53;
    final public const TIER_T5_4 = 54;
    final public const TIER_T6 = 60;
    final public const TIER_T6_1 = 61;
    final public const TIER_T6_2 = 62;
    final public const TIER_T6_3 = 63;
    final public const TIER_T6_4 = 64;
    final public const TIER_T7 = 70;
    final public const TIER_T7_1 = 71;
    final public const TIER_T7_2 = 72;
    final public const TIER_T7_3 = 73;
    final public const TIER_T7_4 = 74;
    final public const TIER_T8 = 80;
    final public const TIER_T8_1 = 81;
    final public const TIER_T8_2 = 82;
    final public const TIER_T8_3 = 83;
    final public const TIER_T8_4 = 84;

    #[Column(type: 'integer')]
    #[Id, GeneratedValue(strategy: 'AUTO')]
    protected ?int $id = null;
    #[Column(type: 'integer', nullable: true)]
    protected ?int $tier = null;
    #[Column(type: 'string', nullable: true)]
    protected ?string $name = null;
    #[Column(type: 'string', nullable: true)]
    protected ?string $city = null;
    #[Column(type: 'integer', nullable: true)]
    protected ?int $sellOrderPrice = 0;
    #[Column(type: 'datetime_immutable', nullable: true)]
    protected ?DateTimeImmutable $sellOrderDate = null;
    #[Column(type: 'integer', nullable: true)]
    protected ?int $buyOrderPrice = 0;
    #[Column(type: 'datetime_immutable', nullable: true)]
    protected ?DateTimeImmutable $buyOrderDate = null;
    #[Column(type: 'string', nullable: true)]
    protected ?string $realName = null;
    #[Column(type: 'string', nullable: true)]
    protected ?string $class = null;

    public function setTier(int $tier): self
    {
        $this->tier = $tier;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function setRealName(string $realName): self
    {
        $this->realName = $realName;
        return $this;
    }

    public function setClass(?string $class): self
    {
        $this->class = $class;
        return $this;
    }

    public function setSellOrderPrice(int $sellOrderPrice): self
    {
        $this->sellOrderPrice = $sellOrderPrice;
        return $this;
    }

    public function setSellOrderDate(DateTimeImmutable $sellOrderDate): self
    {
        $this->sellOrderDate = $sellOrderDate;
        return $this;
    }

    public function setBuyOrderPrice(int $buyOrderPrice): self
    {
        $this->buyOrderPrice = $buyOrderPrice;
        return $this;
    }

    public function setBuyOrderDate(DateTimeImmutable $buyOrderDate): self
    {
        $this->buyOrderDate = $buyOrderDate;
        return $this;
    }

    public function calculateBuyOrderDate(?string $dateString): self
    {
        $this->buyOrderDate = $this->calculateDateTimeImmutable($dateString);
        return $this;
    }

    public function calculateSellOrderDate(?string $dateString): self
    {
        $this->sellOrderDate = $this->calculateDateTimeImmutable($dateString);
        return $this;
    }

    private function calculateDateTimeImmutable(?string $dateString): DateTimeImmutable|false
    {
        if ($dateString === null) {
            return DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '1970-03-22 12:12:12');
        }
        $dateString = str_replace('T', ' ', $dateString);
        return DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dateString, new DateTimeZone('Europe/London'));
    }

    public function getTier(): int
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

    public function getSellOrderDate(): ?DateTimeImmutable
    {
        return $this->sellOrderDate;
    }

    public function getBuyOrderPrice(): int
    {
        return $this->buyOrderPrice;
    }

    public function getBuyOrderDate(): ?DateTimeImmutable
    {
        return $this->buyOrderDate;
    }

    public function getRealName(): ?string
    {
        return $this->realName;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }
}
