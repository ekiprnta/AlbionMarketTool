<?php

namespace MZierdt\Albion\Entity\AdvancedEntities;

use MZierdt\Albion\Entity\ResourceEntity;

class TransmutationEntity
{
    private float $profit;
    private string $profitGrade;
    private int $startTierColor;
    private int $endTierColor;
    private ResourceEntity $startResource;
    private ResourceEntity $endResource;
    private float $transmutePrice;

    public function __construct(
        private readonly string $pathName,
        private readonly array $transmutationPath,
        private readonly string $resourceType,
    ) {
    }

    public function getResourceType(): string
    {
        return $this->resourceType;
    }

    public function setStartTierColor(int $startTierColor): void
    {
        $this->startTierColor = $startTierColor;
    }

    public function setEndTierColor(int $endTierColor): void
    {
        $this->endTierColor = $endTierColor;
    }

    public function setStartResource(ResourceEntity $startResource): void
    {
        $this->startResource = $startResource;
    }

    public function setEndResource(ResourceEntity $endResource): void
    {
        $this->endResource = $endResource;
    }

    public function setTransmutePrice(float $transmutePrice): void
    {
        $this->transmutePrice = $transmutePrice;
    }

    public function getPathName(): string
    {
        return $this->pathName;
    }

    public function getTransmutationPath(): array
    {
        return $this->transmutationPath;
    }

    public function getEndTierColor(): int
    {
        return $this->endTierColor;
    }

    public function setProfit(float $profit): void
    {
        $this->profit = $profit;
    }

    public function getProfitGrade(): string
    {
        return $this->profitGrade;
    }

    public function setProfitGrade(string $profitGrade): void
    {
        $this->profitGrade = $profitGrade;
    }

    public function getStartTierColor(): int
    {
        return $this->startTierColor;
    }

    public function getProfit(): float
    {
        return $this->profit;
    }

    public function getStartResource(): ResourceEntity
    {
        return $this->startResource;
    }

    public function getEndResource(): ResourceEntity
    {
        return $this->endResource;
    }

    public function getTransmutePrice(): float
    {
        return $this->transmutePrice;
    }
}
