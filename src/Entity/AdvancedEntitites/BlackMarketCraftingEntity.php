<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity\AdvancedEntitites;

use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\JournalEntity;
use MZierdt\Albion\Entity\ResourceEntity;
use MZierdt\Albion\factories\ResourceEntityFactory;

/*
 * beide Resourcen
 * Item
 * Preis
 * Journal
 * Gewicht
 * AMount
 * Profit
 * Etc
 *
 */
class BlackMarketCraftingEntity
{
    private ResourceEntity $primResource;
    private ResourceEntity $secResource;

    private JournalEntity $journalEntityEmpty;
    private JournalEntity $journalEntityFull;
    private float $journalAmountPerItem;

    private int $totalAmount;
    private int $primResourceAmount;
    private int $secResourceAmount;
    private float $journalAmount;

    private float $craftingFee;
    private float $profitJournals;
    private float $profit;
    private string $colorGrade;

    private float $fameAmount;
    private readonly int $tierColor;
    private int $itemValue;
    private float $profitPercentage;

    public function __construct(
        private readonly ItemEntity $item,
    ) {
        $this->secResource = ResourceEntityFactory::getEmptyResourceEntity();
        $this->tierColor = (int) ($item->getTier() / 10);
    }

    public function getProfitPercentage(): float
    {
        return $this->profitPercentage;
    }

    public function setProfitPercentage(float $profitPercentage): void
    {
        $this->profitPercentage = $profitPercentage;
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

    public function getTierColor(): int
    {
        return $this->tierColor;
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

    public function getColorGrade(): string
    {
        return $this->colorGrade;
    }

    public function setColorGrade(string $colorGrade): void
    {
        $this->colorGrade = $colorGrade;
    }

    public function getProfitJournals(): float
    {
        return $this->profitJournals;
    }

    public function setProfitJournals(float $profitJournals): void
    {
        $this->profitJournals = $profitJournals;
    }

    public function getProfit(): float
    {
        return $this->profit;
    }

    public function setProfit(float $profit): void
    {
        $this->profit = $profit;
    }

    public function getCraftingFee(): float
    {
        return $this->craftingFee;
    }

    public function setCraftingFee(float $craftingFee): void
    {
        $this->craftingFee = $craftingFee;
    }

    public function getTotalAmount(): int
    {
        return $this->totalAmount;
    }

    public function getPrimResourceAmount(): int
    {
        return $this->primResourceAmount;
    }

    public function getSecResourceAmount(): int
    {
        return $this->secResourceAmount;
    }

    public function getJournalAmount(): float
    {
        return $this->journalAmount;
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

    public function setTotalAmount(int $totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }

    public function setPrimResourceAmount(int $primResourceAmount): void
    {
        $this->primResourceAmount = $primResourceAmount;
    }

    public function setSecResourceAmount(int $secResourceAmount): void
    {
        $this->secResourceAmount = $secResourceAmount;
    }

    public function setJournalAmount(float $journalAmount): void
    {
        $this->journalAmount = $journalAmount;
    }
}
