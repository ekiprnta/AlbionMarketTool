<?php

namespace MZierdt\Albion\Entity;

class TransmutationEntity
{
    private float $profit;

    public function __construct(
        private readonly ResourceEntity $startResource,
        private readonly ResourceEntity $endResource,
        private readonly float $transmutePrice
    ) {
        $this->profit = $startResource->getSellOrderPrice() + $endResource->getSellOrderPrice() + $this->transmutePrice;
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

    public function getTransmutePrice(): int
    {
        return $this->transmutePrice;
    }
}