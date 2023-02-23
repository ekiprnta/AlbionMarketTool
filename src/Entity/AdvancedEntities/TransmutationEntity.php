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
#[Table(name: 'transmutation')]
class TransmutationEntity extends MarketEntity
{
    #[ManyToOne(targetEntity: ResourceEntity::class, cascade: ['persist'])]
    #[JoinColumn(name: 'startResource', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ResourceEntity $startResource;

    #[ManyToOne(targetEntity: ResourceEntity::class, cascade: ['persist'])]
    #[JoinColumn(name: 'endResource', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ResourceEntity $endResource;

    #[Column(type: 'string', nullable: true)]
    private ?string $pathName = null;
    #[Column(type: 'simple_array', nullable: true)]
    private ?array $transmutationPath = null;
    #[Column(type: 'string', nullable: true)]
    private ?string $resourceType = null;
    #[Column(type: 'integer', nullable: true)]
    private ?int $endTierColor = null;
    #[Column(type: 'float', nullable: true)]
    private ?float $transmutationPrice = null;

    public function __construct(string $pathName, array $transmutationPath, string $resourceType)
    {
        $this->pathName = $pathName;
        $this->transmutationPath = $transmutationPath;
        $this->resourceType = $resourceType;
    }

    public function getTransmutationPrice(): ?float
    {
        return $this->transmutationPrice;
    }

    public function setTransmutationPrice(?float $transmutationPrice): void
    {
        $this->transmutationPrice = $transmutationPrice;
    }

    public function getResourceType(): string
    {
        return $this->resourceType;
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

    public function getStartResource(): ResourceEntity
    {
        return $this->startResource;
    }

    public function getEndResource(): ResourceEntity
    {
        return $this->endResource;
    }
}
