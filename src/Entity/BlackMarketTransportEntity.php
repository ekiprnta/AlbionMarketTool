<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity;

class BlackMarketTransportEntity
{
    private ItemEntity $bmItem;

    private ItemEntity $cityItem;

    private int $amount;
    private float $profit;
    private float $singleProfit;
    private float $weightProfitQuotient;
    private string $profitGrade;

    private string $tierColor;

    public function __construct(
        ItemEntity $bmItem,
        private int $weight
    ) {
        $this->bmItem = $bmItem;
        $this->tierColor = $bmItem->getTier()[0];
    }

    public function getBmItem(): ItemEntity
    {
        return $this->bmItem;
    }

    public function getTierColor(): string
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

    public function setProfit(array $profitArray): void
    {
        $this->amount = $profitArray['amount'];
        $this->profit = $profitArray['profit'];
        $this->singleProfit = $profitArray['singleProfit'];
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

    public function getWeight(): int
    {
        return $this->weight;
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
