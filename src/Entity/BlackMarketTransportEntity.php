<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity;

use DateTimeImmutable;

class BlackMarketTransportEntity
{
    private string $tier;
    private string $name;
    private string $realName;
    private int $quality;
    private float $weight;
    private int $tierColor;

    private int $bmPrice;
    private DateTimeImmutable $bmPriceDate;
    private ?int $bmPriceAge = null;

    private ?int $cityPrice = null;
    private ?DateTimeImmutable $cityPriceDate = null;
    private ?int $cityPriceAge = null;
    private ?int $cityProfit = null;
    private ?float $cityWeightProfitQuotient = null;
    private string $cityColorGrade = 'D';

    private ?int $amount = null;
    private ?int $totalProfit = null;

    public function __construct(ItemEntity $bmItemData)
    {
        $this->tier = $bmItemData->getTier();
        $this->name = $bmItemData->getName();
        $this->realName = $bmItemData->getRealName();
        $this->quality = $bmItemData->getQuality();
        $this->weight = $bmItemData->getWeight();
        $this->tierColor = $this->setTierColor();

        $this->bmPrice = $bmItemData->getSellOrderPrice();
        $this->bmPriceDate = $bmItemData->getSellOrderPriceDate();
    }

    public function getBmPriceAge(): ?int
    {
        return $this->bmPriceAge;
    }

    public function setBmPriceAge(?int $bmPriceAge): void
    {
        $this->bmPriceAge = $bmPriceAge;
    }

    public function getCityPriceAge(): ?int
    {
        return $this->cityPriceAge;
    }

    public function getTotalProfit(): ?int
    {
        return $this->totalProfit;
    }

    public function setTotalProfit(int $totalProfit): void
    {
        $this->totalProfit = $totalProfit;
    }

    public function setCityPriceAge(?int $cityPriceAge): void
    {
        $this->cityPriceAge = $cityPriceAge;
    }

    public function getTierColor(): int
    {
        return $this->tierColor;
    }

    private function setTierColor(): int
    {
        if (str_starts_with($this->tier, '2')) {
            return 2;
        }
        if (str_starts_with($this->tier, '3')) {
            return 3;
        }
        if (str_starts_with($this->tier, '4')) {
            return 4;
        }
        if (str_starts_with($this->tier, '5')) {
            return 5;
        }
        if (str_starts_with($this->tier, '6')) {
            return 6;
        }
        if (str_starts_with($this->tier, '7')) {
            return 7;
        }
        if (str_starts_with($this->tier, '8')) {
            return 8;
        }
        throw new \RuntimeException('No string found');
    }

    public function getTier(): string
    {
        return $this->tier;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRealName(): string
    {
        return $this->realName;
    }

    public function getQuality(): int
    {
        return $this->quality;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function getBmPrice(): int
    {
        return $this->bmPrice;
    }

    public function getBmPriceDate(): DateTimeImmutable|bool
    {
        return $this->bmPriceDate;
    }

    public function getCityPrice(): ?int
    {
        return $this->cityPrice;
    }

    public function setCityPrice(?int $cityPrice): void
    {
        $this->cityPrice = $cityPrice;
    }

    public function getCityPriceDate(): ?DateTimeImmutable
    {
        return $this->cityPriceDate;
    }

    public function setCityPriceDate(?DateTimeImmutable $cityPriceDate): void
    {
        $this->cityPriceDate = $cityPriceDate;
    }

    public function getCityProfit(): ?int
    {
        return $this->cityProfit;
    }

    public function setCityProfit(?int $cityProfit): void
    {
        $this->cityProfit = $cityProfit;
    }

    public function getCityWeightProfitQuotient(): ?float
    {
        return $this->cityWeightProfitQuotient;
    }

    public function setCityWeightProfitQuotient(?float $cityWeightProfitQuotient): void
    {
        $this->cityWeightProfitQuotient = $cityWeightProfitQuotient;
    }

    public function getCityColorGrade(): string
    {
        return $this->cityColorGrade;
    }

    public function setCityColorGrade(string $cityColorGrade): void
    {
        $this->cityColorGrade = $cityColorGrade;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }
}
