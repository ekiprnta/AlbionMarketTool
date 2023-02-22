<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity\AdvancedEntities;

use Doctrine\ORM\Mapping\ChangeTrackingPolicy;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\MaterialEntity;
use MZierdt\Albion\Entity\ResourceEntity;

#[Entity]
#[ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Table(name: 'enchanting')]
class EnchantingEntity extends MarketEntity
{
    #[ManyToOne(targetEntity: ResourceEntity::class, cascade: ['persist'])]
    #[JoinColumn(name: 'higherEnchantmentItem', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ItemEntity $higherEnchantmentItem;

    #[ManyToOne(targetEntity: ResourceEntity::class, cascade: ['persist'])]
    #[JoinColumn(name: 'enchantmentMaterial', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private MaterialEntity $enchantmentMaterial;

    #[ManyToOne(targetEntity: ResourceEntity::class, cascade: ['persist'])]
    #[JoinColumn(name: 'baseItem', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ItemEntity $baseItem;

    #[Column(type: 'integer', nullable: true)]
    private ?int $baseEnchantment;
    #[Column(type: 'integer', nullable: true)]
    private ?int $materialAmount;

    public function __construct(ItemEntity $baseItem)
    {
        $this->baseItem = $baseItem;
        $this->tierColor = (int) ($baseItem->getTier() / 10);
    }

    public function getMaterialAmount(): int
    {
        return $this->materialAmount;
    }

    public function setMaterialAmount(int $materialAmount): void
    {
        $this->materialAmount = $materialAmount;
    }

    public function getEnchantmentMaterial(): MaterialEntity
    {
        return $this->enchantmentMaterial;
    }

    public function setEnchantmentMaterial(MaterialEntity $enchantmentMaterial): void
    {
        $this->enchantmentMaterial = $enchantmentMaterial;
    }

    public function getHigherEnchantmentItem(): ItemEntity
    {
        return $this->higherEnchantmentItem;
    }

    public function setHigherEnchantmentItem(ItemEntity $higherEnchantmentItem): void
    {
        $this->higherEnchantmentItem = $higherEnchantmentItem;
    }

    public function getBaseItem(): ItemEntity
    {
        return $this->baseItem;
    }

    public function getBaseEnchantment(): int
    {
        return $this->baseEnchantment;
    }

    public function setBaseEnchantment(int $baseEnchantment): void
    {
        $this->baseEnchantment = $baseEnchantment;
    }
}
