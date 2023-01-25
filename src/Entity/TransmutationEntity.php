<?php

namespace MZierdt\Albion\Entity;

class TransmutationEntity
{
    private float $profit;
    private string $profitGrade;
    private string $startTierColor;
    private string $endTierColor;
    private string $pathToTransmute;

    public function __construct(
        private readonly ResourceEntity $startResource,
        private readonly ResourceEntity $endResource,
        private readonly float $transmutePrice
    ) {
        $this->startTierColor = $this->startResource->getTier()[0];
        $this->endTierColor = $this->endResource->getTier()[0];
    }

    public function getEndTierColor(): string
    {
        return $this->endTierColor;
    }

    public function getPathToTransmute(): string
    {
        return $this->pathToTransmute;
    }

    public function setPathToTransmute(string $pathToTransmute): void
    {
        $this->pathToTransmute = $pathToTransmute;
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

    public function getStartTierColor(): string
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
