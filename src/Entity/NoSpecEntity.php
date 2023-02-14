<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity;

class NoSpecEntity
{
    private ItemEntity $defaultCape;
    private MaterialEntity $secondResource;
    private ?MaterialEntity $artifact;

    public function __construct(private readonly ItemEntity $specialCape)
    {
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

    public function getArtifact(): MaterialEntity
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