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

    private ?int $fsPrice = null;
    private ?DateTimeImmutable $fsPriceDate = null;
    private ?int $fsProfit = null;
    private ?float $fsWeightProfitQuotient = null;
    private string $fsColorGrade = 'D';

    private ?int $lymPrice = null;
    private ?DateTimeImmutable $lymPriceDate = null;
    private ?int $lymProfit = null;
    private ?float $lymWeightProfitQuotient = null;
    private string $lymColorGrade = 'D';

    private ?int $bwPrice = null;
    private ?DateTimeImmutable $bwPriceDate = null;
    private ?int $bwProfit = null;
    private ?float $bwWeightProfitQuotient = null;
    private string $bwColorGrade = 'D';

    private ?int $mlPrice = null;
    private ?DateTimeImmutable $mlPriceDate = null;
    private ?int $mlProfit = null;
    private ?float $mlWeightProfitQuotient = null;
    private string $mlColorGrade = 'D';

    private ?int $thetPrice = null;
    private ?DateTimeImmutable $thetPriceDate = null;
    private ?int $thetProfit = null;
    private ?float $thetWeightProfitQuotient = null;
    private string $thetColorGrade = 'D';

    private int $amount;

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

    public function getFsColorGrade(): string
    {
        return $this->fsColorGrade;
    }

    public function setFsColorGrade(string $fsColorGrade): void
    {
        $this->fsColorGrade = $fsColorGrade;
    }

    public function getLymColorGrade(): string
    {
        return $this->lymColorGrade;
    }

    public function setLymColorGrade(string $lymColorGrade): void
    {
        $this->lymColorGrade = $lymColorGrade;
    }

    public function getBwColorGrade(): string
    {
        return $this->bwColorGrade;
    }

    public function setBwColorGrade(string $bwColorGrade): void
    {
        $this->bwColorGrade = $bwColorGrade;
    }

    public function getMlColorGrade(): string
    {
        return $this->mlColorGrade;
    }

    public function setMlColorGrade(string $mlColorGrade): void
    {
        $this->mlColorGrade = $mlColorGrade;
    }

    public function getThetColorGrade(): string
    {
        return $this->thetColorGrade;
    }

    public function setThetColorGrade(string $thetColorGrade): void
    {
        $this->thetColorGrade = $thetColorGrade;
    }

    public function getFsWeightProfitQuotient(): ?float
    {
        return $this->fsWeightProfitQuotient;
    }

    public function setFsWeightProfitQuotient(float $fsWeightProfitQuotient): void
    {
        $this->fsWeightProfitQuotient = $fsWeightProfitQuotient;
    }

    public function getLymWeightProfitQuotient(): ?float
    {
        return $this->lymWeightProfitQuotient;
    }

    public function setLymWeightProfitQuotient(float $lymWeightProfitQuotient): void
    {
        $this->lymWeightProfitQuotient = $lymWeightProfitQuotient;
    }

    public function getBwWeightProfitQuotient(): ?float
    {
        return $this->bwWeightProfitQuotient;
    }

    public function setBwWeightProfitQuotient(float $bwWeightProfitQuotient): void
    {
        $this->bwWeightProfitQuotient = $bwWeightProfitQuotient;
    }

    public function getMlWeightProfitQuotient(): ?float
    {
        return $this->mlWeightProfitQuotient;
    }

    public function setMlWeightProfitQuotient(float $mlWeightProfitQuotient): void
    {
        $this->mlWeightProfitQuotient = $mlWeightProfitQuotient;
    }

    public function getThetWeightProfitQuotient(): ?float
    {
        return $this->thetWeightProfitQuotient;
    }

    public function setThetWeightProfitQuotient(float $thetWeightProfitQuotient): void
    {
        $this->thetWeightProfitQuotient = $thetWeightProfitQuotient;
    }

    public function getFsProfit(): ?int
    {
        return $this->fsProfit;
    }

    public function setFsProfit(int $fsProfit): void
    {
        $this->fsProfit = $fsProfit;
    }

    public function getLymProfit(): ?int
    {
        return $this->lymProfit;
    }

    public function setLymProfit(int $lymProfit): void
    {
        $this->lymProfit = $lymProfit;
    }

    public function getBwProfit(): ?int
    {
        return $this->bwProfit;
    }

    public function setBwProfit(int $bwProfit): void
    {
        $this->bwProfit = $bwProfit;
    }

    public function getMlProfit(): ?int
    {
        return $this->mlProfit;
    }

    public function setMlProfit(int $mlProfit): void
    {
        $this->mlProfit = $mlProfit;
    }

    public function getThetProfit(): ?int
    {
        return $this->thetProfit;
    }

    public function setThetProfit(int $thetProfit): void
    {
        $this->thetProfit = $thetProfit;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
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

    public function getFsPrice(): ?int
    {
        return $this->fsPrice;
    }

    public function setFsPrice(int $fsPrice): void
    {
        $this->fsPrice = $fsPrice;
    }

    public function getFsPriceDate(): DateTimeImmutable
    {
        return $this->fsPriceDate;
    }

    public function setFsPriceDate(DateTimeImmutable $fsPriceDate): void
    {
        $this->fsPriceDate = $fsPriceDate;
    }

    public function getLymPrice(): ?int
    {
        return $this->lymPrice;
    }

    public function setLymPrice(int $lymPrice): void
    {
        $this->lymPrice = $lymPrice;
    }

    public function getLymPriceDate(): DateTimeImmutable
    {
        return $this->lymPriceDate;
    }

    public function setLymPriceDate(DateTimeImmutable $lymPriceDate): void
    {
        $this->lymPriceDate = $lymPriceDate;
    }

    public function getBwPrice(): ?int
    {
        return $this->bwPrice;
    }

    public function setBwPrice(int $bwPrice): void
    {
        $this->bwPrice = $bwPrice;
    }

    public function getBwPriceDate(): DateTimeImmutable
    {
        return $this->bwPriceDate;
    }

    public function setBwPriceDate(DateTimeImmutable $bwPriceDate): void
    {
        $this->bwPriceDate = $bwPriceDate;
    }

    public function getMlPrice(): ?int
    {
        return $this->mlPrice;
    }

    public function setMlPrice(int $mlPrice): void
    {
        $this->mlPrice = $mlPrice;
    }

    public function getMlPriceDate(): DateTimeImmutable
    {
        return $this->mlPriceDate;
    }

    public function setMlPriceDate(DateTimeImmutable $mlPriceDate): void
    {
        $this->mlPriceDate = $mlPriceDate;
    }

    public function getThetPrice(): ?int
    {
        return $this->thetPrice;
    }

    public function setThetPrice(int $thetPrice): void
    {
        $this->thetPrice = $thetPrice;
    }

    public function getThetPriceDate(): DateTimeImmutable
    {
        return $this->thetPriceDate;
    }

    public function setThetPriceDate(DateTimeImmutable $thetPriceDate): void
    {
        $this->thetPriceDate = $thetPriceDate;
    }
}
