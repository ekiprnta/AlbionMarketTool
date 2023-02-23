<?php

namespace MZierdt\Albion\Entity\AdvancedEntities;

use Doctrine\ORM\Mapping\ChangeTrackingPolicy;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use MZierdt\Albion\Entity\ResourceEntity;

#[Entity]
#[ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Table(name: 'refining')]
class RefiningEntity extends MarketEntity
{
    #[ManyToOne(targetEntity: ResourceEntity::class, cascade: ['persist'])]
    #[JoinColumn(name: 'rawResource', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ResourceEntity $rawResource;

    #[ManyToOne(targetEntity: ResourceEntity::class, cascade: ['persist'])]
    #[JoinColumn(name: 'lowerResource', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ResourceEntity $lowerResource;

    #[ManyToOne(targetEntity: ResourceEntity::class, cascade: ['persist'])]
    #[JoinColumn(name: 'refinedResource', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ResourceEntity $refinedResource;

    #[Column(type: 'integer', nullable: true)]
    private ?int $amountRawResource = null;

    public function __construct(ResourceEntity $resourceEntity)
    {
        $this->refinedResource = $resourceEntity;
        $this->tierColor = (int) ($resourceEntity->getTier() / 10);
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

    public function getAmountRawResource(): ?int
    {
        return $this->amountRawResource;
    }

    public function setAmountRawResource(?int $amountRawResource): void
    {
        $this->amountRawResource = $amountRawResource;
    }

    public function getRefinedResource(): ResourceEntity
    {
        return $this->refinedResource;
    }
}
