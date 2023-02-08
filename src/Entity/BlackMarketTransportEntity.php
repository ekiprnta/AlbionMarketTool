<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity;

class BlackMarketTransportEntity
{
    private ItemEntity $cityItem;

    private int $amount;
    private float $profit;
    private float $singleProfit;
    private float $weightProfitQuotient;
    private string $profitGrade;

    private int $tierColor;
    private float $totalCost;
    private float $profitPercentage;

    public function __construct(
        private readonly ItemEntity $bmItem,
    ) {
        $this->tierColor = (int) ($bmItem->getTier() / 10);
    }

    public function getTotalCost(): float
    {
        return $this->totalCost;
    }

    public function setTotalCost(float $totalCost): void
    {
        $this->totalCost = $totalCost;
    }

    public function getProfitPercentage(): float
    {
        return $this->profitPercentage;
    }

    public function setProfitPercentage(float $profitPercentage): void
    {
        $this->profitPercentage = $profitPercentage;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function setSingleProfit(float $singleProfit): void
    {
        $this->singleProfit = $singleProfit;
    }

    public function getBmItem(): ItemEntity
    {
        return $this->bmItem;
    }

    public function getTierColor(): int
    {
        return $this->tierColor;
    }

    public function getProfitGrade(): string
    {
        return $this->profitGrade;
    }

    public function setProfitGrade(string $profitGrade): void
    {
        $this->profitGrade = $profitGrade;
    }

    public function getWeightProfitQuotient(): float
    {
        return $this->weightProfitQuotient;
    }

    public function setWeightProfitQuotient(float $weightProfitQuotient): void
    {
        $this->weightProfitQuotient = $weightProfitQuotient;
    }

    public function setProfit(float $profit): void
    {
        $this->profit = $profit;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getProfit(): float
    {
        return $this->profit;
    }

    public function getSingleProfit(): float
    {
        return $this->singleProfit;
    }

    public function getCityItem(): ItemEntity
    {
        return $this->cityItem;
    }

    public function setCityItem(ItemEntity $cityItem): void
    {
        $this->cityItem = $cityItem;
    }
}
