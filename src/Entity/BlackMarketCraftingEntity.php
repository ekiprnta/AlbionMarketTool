<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity;

use DateTimeImmutable;
use MZierdt\Albion\factories\ResourceEntityFactory;
use RuntimeException;
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
    private int $journalAmount;
    private float $totalItemWeight;

    private float $craftingFee;
    private float $profitBooks;
    private float $profit;
    private float $weightProfitQuotient;
    private string $colorGrade;

    private int $primAge;
    private int $secAge;
    private int $itemAge;

    private int $fameAmount;

    private int $itemValue;

    public function __construct(private ItemEntity $item, private int $totalWeightResources)
    {
        $this->secResource = ResourceEntityFactory::getEmptyResourceEntity();
    }

    public function setItemValue(int $itemValue): void
    {
        $this->itemValue = $itemValue;
    }

    public function getItemValue(): int
    {
        return $this->itemValue;
    }

    public function getFameAmount(): int
    {
        return $this->fameAmount;
    }

    public function setFameAmount(int $fameAmount): void
    {
        $this->fameAmount = $fameAmount;
    }

    public function getItemAge(): int
    {
        return $this->itemAge;
    }

    public function setItemAge(int $itemAge): void
    {
        $this->itemAge = $itemAge;
    }

    public function getSecAge(): int
    {
        return $this->secAge;
    }

    public function getPrimAge(): int
    {
        return $this->primAge;
    }

    public function getColorGrade(): string
    {
        return $this->colorGrade;
    }

    public function setColorGrade(string $colorGrade): void
    {
        $this->colorGrade = $colorGrade;
    }

    public function getWeightProfitQuotient(): float
    {
        return $this->weightProfitQuotient;
    }

    public function setWeightProfitQuotient(float $weightProfitQuotient): void
    {
        $this->weightProfitQuotient = $weightProfitQuotient;
    }

    public function getProfitBooks(): float
    {
        return $this->profitBooks;
    }

    public function setProfitBooks(float $profitBooks): void
    {
        $this->profitBooks = $profitBooks;
    }

    public function getProfit(): float
    {
        return $this->profit;
    }

    public function setProfit(array $profitArray): void
    {
        $this->profit = $profitArray['profit'];
        $this->primAge = $profitArray['primAge'];
        $this->secAge = $profitArray['secAge'];
    }

    public function getCraftingFee(): float
    {
        return $this->craftingFee;
    }

    public function setCraftingFee(float $craftingFee): void
    {
        $this->craftingFee = $craftingFee;
    }

    public function getTotalWeightResources(): int
    {
        return $this->totalWeightResources;
    }

    public function getTotalItemWeight(): float
    {
        return $this->totalItemWeight;
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

    public function getJournalAmount(): int
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

    public function setResources(array $resources): void
    {
        $this->primResource = $resources['primaryResource'] ?? ResourceEntityFactory::getEmptyResourceEntity();
        $this->secResource = $resources['secondaryResource'] ?? ResourceEntityFactory::getEmptyResourceEntity();
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

    public function setJournals(array $journals): void
    {
        $this->journalAmountPerItem = $journals['amount'];
        $this->journalEntityFull = $journals['full'];
        $this->journalEntityEmpty = $journals['empty'];
    }

    public function setAmounts(array $amounts): void
    {
        $this->totalAmount = $amounts['totalAmount'];
        $this->primResourceAmount = $amounts['primResourceAmount'];
        $this->secResourceAmount = $amounts['secResourceAmount'];
        $this->journalAmount = $amounts['journalAmount'];
        $this->totalItemWeight = $amounts['totalItemWeight'];
    }
}