<?php

namespace MZierdt\Albion\Entity;

class TransmutationEntity
{
    public function __construct(
        private readonly ResourceEntity $startResource,
        private readonly ResourceEntity $endResource,
        private readonly int $transmutePrice
    ) {
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