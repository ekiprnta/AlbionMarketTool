<?php

namespace MZierdt\Albion\Entity;

class RefiningEntity
{
    private int $amountRawResource;

    private ResourceEntity $rawResource;
    private ResourceEntity $lowerResource;

    private float $singleProfit;
    private int $amount;
    private float $profit;

    private float $profitQuotient;
    private string $profitGrade;
    private readonly int $tierColor;

    public function __construct(private readonly ResourceEntity $resourceEntity)
    {
        $this->tierColor = (int) ($this->resourceEntity->getTier() / 10);
    }

    public function getTierColor(): int
    {
        return $this->tierColor;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getProfit(): float
    {
        return $this->profit;
    }

    public function setProfit(float $profit): void
    {
        $this->profit = $profit;
    }

    public function getProfitQuotient(): float
    {
        return $this->profitQuotient;
    }

    public function setProfitQuotient(float $profitQuotient): void
    {
        $this->profitQuotient = $profitQuotient;
    }

    public function getProfitGrade(): string
    {
        return $this->profitGrade;
    }

    public function setProfitGrade(string $profitGrade): void
    {
        $this->profitGrade = $profitGrade;
    }

    public function getSingleProfit(): float
    {
        return $this->singleProfit;
    }

    public function setSingleProfit(float $singleProfit): void
    {
        $this->singleProfit = $singleProfit;
    }

    public function getRawResource(): ResourceEntity
    {
        return $this->rawResource;
    }

    public function setRawResource(ResourceEntity $rawResource): void
    {
        $this->rawResource = $rawResource;
    }

    public function getLowerResource(): ResourceEntity
    {
        return $this->lowerResource;
    }

    public function setLowerResource(ResourceEntity $lowerResource): void
    {
        $this->lowerResource = $lowerResource;
    }

    public function getAmountRawResource(): int
    {
        return $this->amountRawResource;
    }

    public function setAmountRawResource(int $amountRawResource): void
    {
        $this->amountRawResource = $amountRawResource;
    }

    public function getResourceEntity(): ResourceEntity
    {
        return $this->resourceEntity;
    }
}
