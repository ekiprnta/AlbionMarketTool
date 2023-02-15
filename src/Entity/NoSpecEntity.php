<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity;

class NoSpecEntity
{
    private ItemEntity $defaultItem;
    private MaterialEntity $secondResource;
    private ?MaterialEntity $artifact;

    private float $materialCost;
    private float $profit;

    private float $profitQuotient;
    private string $profitGrade;
    private int $tierColor;

    public function __construct(private readonly ItemEntity $specialItem)
    {
        $this->tierColor = (int) ($this->specialItem->getTier() / 10);
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

    public function getTierColor(): int
    {
        return $this->tierColor;
    }

    public function getMaterialCost(): float
    {
        return $this->materialCost;
    }

    public function setMaterialCost(float $materialCost): void
    {
        $this->materialCost = $materialCost;
    }

    public function getDefaultItem(): ItemEntity
    {
        return $this->defaultItem;
    }

    public function setDefaultItem(ItemEntity $defaultItem): void
    {
        $this->defaultItem = $defaultItem;
    }

    public function getSecondResource(): MaterialEntity
    {
        return $this->secondResource;
    }

    public function setSecondResource(MaterialEntity $secondResource): void
    {
        $this->secondResource = $secondResource;
    }

    public function getArtifact(): ?MaterialEntity
    {
        return $this->artifact;
    }

    public function setArtifact(?MaterialEntity $artifact): void
    {
        $this->artifact = $artifact;
    }

    public function getSpecialItem(): ItemEntity
    {
        return $this->specialItem;
    }
}
