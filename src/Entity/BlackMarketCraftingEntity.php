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


    public function __construct(private ItemEntity $item, private int $weight)
    {
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

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function getTotalItemWeight(): float
    {
        return $this->totalItemWeight;
    }

    public function setTotalItemWeight(float $totalItemWeight): void
    {
        $this->totalItemWeight = $totalItemWeight;
    }

    public function getTotalAmount(): int
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(int $totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }

    public function getPrimResourceAmount(): int
    {
        return $this->primResourceAmount;
    }

    public function setPrimResourceAmount(int $primResourceAmount): void
    {
        $this->primResourceAmount = $primResourceAmount;
    }

    public function getSecResourceAmount(): int
    {
        return $this->secResourceAmount;
    }

    public function setSecResourceAmount(int $secResourceAmount): void
    {
        $this->secResourceAmount = $secResourceAmount;
    }

    public function getJournalAmount(): int
    {
        return $this->journalAmount;
    }

    public function setJournalAmount(int $journalAmount): void
    {
        $this->journalAmount = $journalAmount;
    }

    public function getItem(): ItemEntity
    {
        return $this->item;
    }

    public function getPrimResource(): ResourceEntity
    {
        return $this->primResource;
    }

    public function setPrimResource(ResourceEntity $primResource): void
    {
        $this->primResource = $primResource;
    }

    public function getSecResource(): ResourceEntity
    {
        return $this->secResource;
    }

    public function setSecResource(?ResourceEntity $secResource): void
    {
        $this->secResource = $secResource ?? ResourceEntityFactory::getEmtpyResourceEntity();
    }

    public function getJournalEntityEmpty(): JournalEntity
    {
        return $this->journalEntityEmpty;
    }

    public function setJournalEntityEmpty(JournalEntity $journalEntityEmpty): void
    {
        $this->journalEntityEmpty = $journalEntityEmpty;
    }

    public function getJournalEntityFull(): JournalEntity
    {
        return $this->journalEntityFull;
    }

    public function setJournalEntityFull(JournalEntity $journalEntityFull): void
    {
        $this->journalEntityFull = $journalEntityFull;
    }

    public function getJournalAmountPerItem(): float
    {
        return $this->journalAmountPerItem;
    }

    public function setJournalAmountPerItem(float $journalAmount): void
    {
        $this->journalAmountPerItem = $journalAmount;
    }
}