<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity\AdvancedEntities;

use Doctrine\ORM\Mapping\ChangeTrackingPolicy;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\MaterialEntity;

#[Entity]
#[ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Table(name: 'noSpec')]
class NoSpecEntity extends MarketEntity
{
    #[ManyToOne(targetEntity: ItemEntity::class, cascade: ['persist'])]
    #[JoinColumn(name: 'defaultItem', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ItemEntity $defaultItem;

    #[ManyToOne(targetEntity: ItemEntity::class, cascade: ['persist'])]
    #[JoinColumn(name: 'specialItem', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ItemEntity $specialItem;

    #[ManyToOne(targetEntity: MaterialEntity::class, cascade: ['persist'])]
    #[JoinColumn(name: 'secondResource', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private MaterialEntity $secondResource;

    #[ManyToOne(targetEntity: MaterialEntity::class, cascade: ['persist'])]
    #[JoinColumn(name: 'artifact', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ?MaterialEntity $artifact;

    public function __construct(ItemEntity $specialItem)
    {
        $this->specialItem = $specialItem;
        $this->tierColor = (int) ($specialItem->getTier() / 10);
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
