<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity;

class NoSpecEntity
{
    private ItemEntity $defaultCape;
    private MaterialEntity $secondResource;
    private ?MaterialEntity $artifact;

    private int $materialCost;
    private float $profit;

    private float $profitQuotient;
    private string $profitGrade;
    private int $tierColor;

    public function __construct(private readonly ItemEntity $specialCape)
    {
        $this->tierColor = (int) ($this->specialCape->getTier() / 10);
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

    public function getMaterialCost(): int
    {
        return $this->materialCost;
    }

    public function setMaterialCost(int $materialCost): void
    {
        $this->materialCost = $materialCost;
    }

    public function getDefaultCape(): ItemEntity
    {
        return $this->defaultCape;
    }

    public function setDefaultCape(ItemEntity $defaultCape): void
    {
        $this->defaultCape = $defaultCape;
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

    public function getSpecialCape(): ItemEntity
    {
        return $this->specialCape;
    }
}