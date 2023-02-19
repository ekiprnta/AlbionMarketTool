<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity\AdvancedEntities;

use Doctrine\ORM\Mapping\ChangeTrackingPolicy;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;
use MZierdt\Albion\Entity\ItemEntity;

#[Entity]
#[ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Table(name: 'bmTransport')]
class BlackMarketTransportEntity extends MarketEntity
{
    #[OneToOne(targetEntity: ItemEntity::class, cascade: ['persist'])]
    #[JoinColumn(name: 'cityItemId', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ItemEntity $cityItem;
    #[ManyToOne(targetEntity: ItemEntity::class, cascade: ['persist'])]
    #[JoinColumn(name: 'bmItemId', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ItemEntity $bmItem;
    #[Column(type: 'string', nullable: true)]
    protected ?string $tierString = null;

    public function getTierString(): ?string
    {
        return $this->tierString;
    }

    public function setTierString(?string $tierString): void
    {
        $this->tierString = $tierString;
    }

    public function __construct(ItemEntity $bmItem
    ) {
        $this->bmItem = $bmItem;
        $this->tierColor = (int) ($bmItem->getTier() / 10);
    }

    public function getCityItem(): ItemEntity
    {
        return $this->cityItem;
    }

    public function setCityItem(ItemEntity $cityItem): void
    {
        $this->cityItem = $cityItem;
    }

    public function getBmItem(): ItemEntity
    {
        return $this->bmItem;
    }
}
