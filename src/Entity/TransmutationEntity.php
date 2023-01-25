<?php

namespace MZierdt\Albion\Entity;

class TransmutationEntity
{
    private float $profit;
    private string $profitGrade;
    private string $tierColor;
    private string $pathToTransmute;

    public function __construct(
        private readonly ResourceEntity $startResource,
        private readonly ResourceEntity $endResource,
        private readonly float $transmutePrice
    ) {
        $this->tierColor = $this->startResource->getTier();
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

    public function getTierColor(): string
    {
        return $this->tierColor;
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