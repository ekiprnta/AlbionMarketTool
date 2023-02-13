<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity;

class EnchantingEntity
{
    private ItemEntity $higherEnchantmentItem;
    private MaterialEntity $enchantmentMaterial;
    private int $baseEnchantment;
    private int $materialAmount;

    private float $materialCost;
    private float $profit;

    private float $profitQuotient;
    private string $profitGrade;
    private readonly int $tierColor;

    public function __construct(private readonly ItemEntity $itemEntity)
    {
        $this->tierColor = (int) ($this->itemEntity->getTier() / 10);
    }

    public function getProfitQuotient(): float
    {
        return $this->profitQuotient;
    }

    public function setProfitQuotient(float $profitQuotient): void
    {
        $this->profitQuotient = $profitQuotient;
    }

    public function getProfitGrade(): string
    {
        return $this->profitGrade;
    }

    public function setProfitGrade(string $profitGrade): void
    {
        $this->profitGrade = $profitGrade;
    }

    public function getTierColor(): int
    {
        return $this->tierColor;
    }

    public function getProfit(): float
    {
        return $this->profit;
    }

    public function setProfit(float $profit): void
    {
        $this->profit = $profit;
    }

    public function getMaterialCost(): float
    {
        return $this->materialCost;
    }

    public function setMaterialCost(float $materialCost): void
    {
        $this->materialCost = $materialCost;
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

    public function getItemEntity(): ItemEntity
    {
        return $this->itemEntity;
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