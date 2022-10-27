<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity;

use DateTimeImmutable;
use RuntimeException;

class CalculateEntity
{
    private const PREMIUM_FACTOR = 1.5;

    private string $tier;
    private string $name;
    private string $weaponGroup;
    private int $itemSellOrderPrice;
    private DateTimeImmutable $itemSellOrderPriceDate;
    private float $fameAmount;
    private float $itemWeight;

    private string $primaryResource;
    private int $primaryResourceAmount;
    private int $primarySellOrderPrice;
    private DateTimeImmutable $primarySellOrderPriceDate;

    private ?string $secondaryResource;
    private ?int $secondaryResourceAmount;
    private ?int $secondarySellOrderPrice = null;
    private ?DateTimeImmutable $secondarySellOrderPriceDate = null;


    private float $resourceWeight;

    private float $percentageProfit;
    private float $totalWeightItems;
    private float $totalWeightResources;

    private float $WeightProfitQuotient;
    private string $colorGrade;
    private float $amount;

    private int $itemPriceAge;
    private int $primaryPriceAge;
    private int $secondaryPriceAge;

    public function __construct(ItemEntity $itemEntity, array $resourceData)
    {
        $primaryResourceEntity = $this->getPrimaryResourceEntity($itemEntity, $resourceData);

        $craftingFame = $this->calculateCraftingFame($itemEntity);
        $resourceWeight = $this->calculateResourceWeight($itemEntity, $primaryResourceEntity);

        $this->tier = $itemEntity->getTier();
        $this->name = $itemEntity->getName();
        $this->weaponGroup = $itemEntity->getWeaponGroup();
        $this->itemSellOrderPrice = $itemEntity->getSellOrderPrice();
        $this->itemSellOrderPriceDate = $itemEntity->getSellOrderPriceDate();
        $this->fameAmount = $craftingFame;
        $this->itemWeight = $itemEntity->getWeight();

        $this->primaryResource = $itemEntity->getPrimaryResource();
        $this->primaryResourceAmount = $itemEntity->getPrimaryResourceAmount();
        $this->primarySellOrderPrice = $primaryResourceEntity->getSellOrderPrice();
        $this->primarySellOrderPriceDate = $primaryResourceEntity->getSellOrderPriceDate();

        $this->secondaryResource = $itemEntity->getSecondaryResource();
        $this->secondaryResourceAmount = $itemEntity->getSecondaryResourceAmount();
        if (! ($this->secondaryResource === null)) {
            $secondaryResourceEntity = $this->getSecondaryResourceEntity($itemEntity, $resourceData);
            $this->secondarySellOrderPrice = $secondaryResourceEntity->getSellOrderPrice();
            $this->secondarySellOrderPriceDate = $secondaryResourceEntity->getSellOrderPriceDate();
        }

        $this->resourceWeight = $resourceWeight;
    }

    public function getItemPriceAge(): int
    {
        return $this->itemPriceAge;
    }

    public function setItemPriceAge(int $itemPriceAge): void
    {
        $this->itemPriceAge = $itemPriceAge;
    }

    public function getPrimaryPriceAge(): int
    {
        return $this->primaryPriceAge;
    }

    public function setPrimaryPriceAge(int $primaryPriceAge): void
    {
        $this->primaryPriceAge = $primaryPriceAge;
    }

    public function getSecondaryPriceAge(): int
    {
        return $this->secondaryPriceAge;
    }

    public function setSecondaryPriceAge(int $secondaryPriceAge): void
    {
        $this->secondaryPriceAge = $secondaryPriceAge;
    }

    public function getItemSellOrderPriceDate(): DateTimeImmutable
    {
        return $this->itemSellOrderPriceDate;
    }

    public function getPrimarySellOrderPriceDate(): DateTimeImmutable
    {
        return $this->primarySellOrderPriceDate;
    }

    public function getSecondarySellOrderPriceDate(): ?DateTimeImmutable
    {
        return $this->secondarySellOrderPriceDate;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
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
        return $this->WeightProfitQuotient;
    }

    public function setWeightProfitQuotient(float $WeightProfitQuotient): void
    {
        $this->WeightProfitQuotient = $WeightProfitQuotient;
    }

    public function getTotalWeightItems(): float
    {
        return $this->totalWeightItems;
    }

    public function setTotalWeightItems(float $totalWeightItems): void
    {
        $this->totalWeightItems = $totalWeightItems;
    }

    public function getTotalWeightResources(): float
    {
        return $this->totalWeightResources;
    }

    public function setTotalWeightResources(float $totalWeightResources): void
    {
        $this->totalWeightResources = $totalWeightResources;
    }

    public function getPercentageProfit(): float
    {
        return $this->percentageProfit;
    }

    public function setPercentageProfit(float $percentageProfit): void
    {
        $this->percentageProfit = $percentageProfit;
    }

    public function getTier(): string
    {
        return $this->tier;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getWeaponGroup(): mixed
    {
        return $this->weaponGroup;
    }

    public function getItemSellOrderPrice(): int
    {
        return $this->itemSellOrderPrice;
    }

    public function getFameAmount(): float
    {
        return $this->fameAmount;
    }

    public function getItemWeight(): float
    {
        return $this->itemWeight;
    }

    public function getPrimaryResource(): string
    {
        return $this->primaryResource;
    }

    public function getPrimaryResourceAmount(): int
    {
        return $this->primaryResourceAmount;
    }

    public function getPrimarySellOrderPrice(): int
    {
        return $this->primarySellOrderPrice;
    }

    public function getSecondaryResource(): ?string
    {
        return $this->secondaryResource;
    }

    public function getSecondaryResourceAmount(): ?int
    {
        return $this->secondaryResourceAmount;
    }

    public function getSecondarySellOrderPrice(): ?int
    {
        return $this->secondarySellOrderPrice;
    }

    public function getResourceWeight(): float
    {
        return $this->resourceWeight;
    }


    private function getPrimaryResourceEntity(ItemEntity $item, array $resourceData): ResourceEntity
    {
        /** @var ResourceEntity $resourceEntity */
        foreach ($resourceData as $resourceEntity) {
            if (($resourceEntity->getTier() === $item->getTier()) && strcasecmp(
                $resourceEntity->getName(),
                $item->getPrimaryResource()
            ) === 0) {
                return $resourceEntity;
            }
        }
        throw new RuntimeException('No Primary Resource found');
    }

    private function getSecondaryResourceEntity(ItemEntity $item, array $resourceData): ResourceEntity
    {
        /** @var ResourceEntity $resourceEntity */
        foreach ($resourceData as $resourceEntity) {
            if (($resourceEntity->getTier() === $item->getTier()) && strcasecmp(
                $resourceEntity->getName(),
                $item->getSecondaryResource()
            ) === 0) {
                return $resourceEntity;
            }
        }
        throw new RuntimeException('No Secondary Resource found');
    }

    private function calculateCraftingFame(ItemEntity $item): float
    {
        return (
            $item->getPrimaryResourceAmount() +
                $item->getSecondaryResourceAmount()) *
                $item->getFameFactor() *
                self::PREMIUM_FACTOR;
    }

    private function calculateResourceWeight(ItemEntity $itemEntity, ResourceEntity $resourceEntity): float
    {
        $amountSecondary = $itemEntity->getSecondaryResourceAmount();
        $amountPrimary = $itemEntity->getPrimaryResourceAmount();
        $weightResource = $resourceEntity->getWeight();
        return ($amountPrimary + $amountSecondary) * $weightResource;
    }
}
