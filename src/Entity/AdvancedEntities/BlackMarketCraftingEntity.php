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
use MZierdt\Albion\Entity\JournalEntity;
use MZierdt\Albion\Entity\ResourceEntity;
use MZierdt\Albion\factories\ResourceEntityFactory;

#[Entity]
#[ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Table(name: 'bmCrafting')]
class BlackMarketCraftingEntity extends MarketEntity
{
    #[ManyToOne(targetEntity: ResourceEntity::class, cascade: ['persist'])]
    #[JoinColumn(name: 'primResource', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ResourceEntity $primResource;

    #[ManyToOne(targetEntity: ResourceEntity::class, cascade: ['persist'])]
    #[JoinColumn(name: 'secResource', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ResourceEntity $secResource;

    #[ManyToOne(targetEntity: ItemEntity::class, cascade: ['persist'])]
    #[JoinColumn(name: 'Item', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ItemEntity $item;

    #[ManyToOne(targetEntity: JournalEntity::class, cascade: ['persist'])]
    #[JoinColumn(name: 'startResource', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private JournalEntity $journalEntityEmpty;
    #[ManyToOne(targetEntity: JournalEntity::class, cascade: ['persist'])]
    #[JoinColumn(name: 'startResource', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private JournalEntity $journalEntityFull;

    #[Column(type: 'float', nullable: true)]
    private ?float $fameAmount;
    #[Column(type: 'float', nullable: true)]
    private ?float $journalAmountPerItem;
    #[Column(type: 'integer', nullable: true)]
    private ?int $itemValue;

    #[Column(type: 'integer', nullable: true)]
    private ?int $primResourceTotalAmount;
    #[Column(type: 'integer', nullable: true)]
    private ?int $secResourceTotalAmount;
    #[Column(type: 'float', nullable: true)]
    private ?float $journalTotalAmount;

    #[Column(type: 'float', nullable: true)]
    private ?float $craftingFee;
    #[Column(type: 'float', nullable: true)]
    private ?float $profitJournals;

    public function __construct(
        ItemEntity $item,
    ) {
        $this->item = $item;
        $this->secResource = ResourceEntityFactory::getEmptyResourceEntity();
        $this->tierColor = (int) ($item->getTier() / 10);
    }

    public function setJournalEntityEmpty(JournalEntity $journalEntityEmpty): void
    {
        $this->journalEntityEmpty = $journalEntityEmpty;
    }

    public function setJournalEntityFull(JournalEntity $journalEntityFull): void
    {
        $this->journalEntityFull = $journalEntityFull;
    }

    public function setJournalAmountPerItem(float $journalAmountPerItem): void
    {
        $this->journalAmountPerItem = $journalAmountPerItem;
    }

    public function setPrimResource(ResourceEntity $primResource): void
    {
        $this->primResource = $primResource;
    }

    public function setSecResource(ResourceEntity $secResource): void
    {
        $this->secResource = $secResource;
    }

    public function setItemValue(int $itemValue): void
    {
        $this->itemValue = $itemValue;
    }

    public function getItemValue(): int
    {
        return $this->itemValue;
    }

    public function getFameAmount(): float
    {
        return $this->fameAmount;
    }

    public function setFameAmount(float $fameAmount): void
    {
        $this->fameAmount = $fameAmount;
    }

    public function getProfitJournals(): float
    {
        return $this->profitJournals;
    }

    public function setProfitJournals(float $profitJournals): void
    {
        $this->profitJournals = $profitJournals;
    }

    public function getCraftingFee(): float
    {
        return $this->craftingFee;
    }

    public function setCraftingFee(float $craftingFee): void
    {
        $this->craftingFee = $craftingFee;
    }

    public function getPrimResourceTotalAmount(): int
    {
        return $this->primResourceTotalAmount;
    }

    public function getSecResourceTotalAmount(): int
    {
        return $this->secResourceTotalAmount;
    }

    public function getJournalTotalAmount(): float
    {
        return $this->journalTotalAmount;
    }

    public function getItem(): ItemEntity
    {
        return $this->item;
    }

    public function getPrimResource(): ResourceEntity
    {
        return $this->primResource;
    }

    public function getSecResource(): ResourceEntity
    {
        return $this->secResource;
    }

    public function getJournalEntityEmpty(): JournalEntity
    {
        return $this->journalEntityEmpty;
    }

    public function getJournalEntityFull(): JournalEntity
    {
        return $this->journalEntityFull;
    }

    public function getJournalAmountPerItem(): float
    {
        return $this->journalAmountPerItem;
    }

    public function setPrimResourceTotalAmount(int $primResourceTotalAmount): void
    {
        $this->primResourceTotalAmount = $primResourceTotalAmount;
    }

    public function setSecResourceTotalAmount(int $secResourceTotalAmount): void
    {
        $this->secResourceTotalAmount = $secResourceTotalAmount;
    }

    public function setJournalTotalAmount(float $journalTotalAmount): void
    {
        $this->journalTotalAmount = $journalTotalAmount;
    }
}
