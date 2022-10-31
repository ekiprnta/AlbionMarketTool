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
    private string $realName;
    private int $itemSellOrderPrice;
    private DateTimeImmutable $itemSellOrderPriceDate;
    private float $fameAmount;
    private float $itemWeight;

    private string $primaryResource;
    private int $primaryResourceAmount;
    private int $primarySellOrderPrice;
    private DateTimeImmutable $primarySellOrderPriceDate;
    private int $primaryBuyOrderPrice;
    private DateTimeImmutable $primaryBuyOrderPriceDate;


    private ?string $secondaryResource;
    private ?int $secondaryResourceAmount;
    private ?int $secondarySellOrderPrice = null;
    private ?DateTimeImmutable $secondarySellOrderPriceDate = null;
    private ?int $secondaryBuyOrderPrice = null;
    private ?DateTimeImmutable $secondaryBuyOrderPriceDate = null;


    private float $resourceWeight;

    private float $percentageProfit;
    private float $totalWeightItems;
    private float $totalWeightResources;

    private float $WeightProfitQuotient;
    private string $colorGrade;
    private float $amount;
    private int $tierColor;

    private int $itemPriceAge;
    private int $primaryPriceAge;
    private ?int $secondaryPriceAge = null;

    private int $fameToFill;
    private string $journalName;
    private float $journalWeight;
    private int $fullSellOrderPrice;
    private DateTimeImmutable $fullSellOrderPriceDate;
    private int $emptySellOrderPrice;
    private DateTimeImmutable $emptySellOrderPriceDate;
    private int $emptyBuyOrderPrice;
    private DateTimeImmutable $emptyBuyOrderPriceDate;
    private int $amountBooks;

    public function __construct(ItemEntity $itemEntity, array $resourceData, array $journalData)
    {
        $primaryResourceEntity = $this->getPrimaryResourceEntity($itemEntity, $resourceData);

        $craftingFame = $this->calculateCraftingFame($itemEntity);
        $resourceWeight = $this->calculateResourceWeight($itemEntity, $primaryResourceEntity);

        $this->tier = $itemEntity->getTier();
        $this->name = $itemEntity->getName();
        $this->weaponGroup = $itemEntity->getWeaponGroup();
        $this->realName = $itemEntity->getRealName();
        $this->itemSellOrderPrice = $itemEntity->getSellOrderPrice();
        $this->itemSellOrderPriceDate = $itemEntity->getSellOrderPriceDate();
        $this->fameAmount = $craftingFame;
        $this->itemWeight = $itemEntity->getWeight();

        $this->primaryResource = $itemEntity->getPrimaryResource();
        $this->primaryResourceAmount = $itemEntity->getPrimaryResourceAmount();
        $this->primarySellOrderPrice = $primaryResourceEntity->getSellOrderPrice();
        $this->primarySellOrderPriceDate = $primaryResourceEntity->getSellOrderPriceDate();
        $this->primaryBuyOrderPrice = $primaryResourceEntity->getBuyOrderPrice();
        $this->primaryBuyOrderPriceDate = $primaryResourceEntity->getBuyOrderPriceDate();

        $this->secondaryResource = $itemEntity->getSecondaryResource();
        $this->secondaryResourceAmount = $itemEntity->getSecondaryResourceAmount();
        if (!($this->secondaryResource === null)) {
            $secondaryResourceEntity = $this->getSecondaryResourceEntity($itemEntity, $resourceData);
            $this->secondarySellOrderPrice = $secondaryResourceEntity->getSellOrderPrice();
            $this->secondarySellOrderPriceDate = $secondaryResourceEntity->getSellOrderPriceDate();
            $this->secondaryBuyOrderPrice = $secondaryResourceEntity->getBuyOrderPrice();
            $this->secondaryBuyOrderPriceDate = $secondaryResourceEntity->getBuyOrderPriceDate();
        }

        $this->tierColor = $this->setTierColor();
        $this->resourceWeight = $resourceWeight;

        $journalInfo = $this->getJournalInfo($itemEntity, $journalData);
        $fame = $journalInfo['full']->getFameToFill();
        $this->fameToFill = $fame;
        $this->journalName = $journalInfo['full']->getName();
        $this->fullSellOrderPrice = $journalInfo['full']->getSellOrderPrice();
        $this->fullSellOrderPriceDate = $journalInfo['full']->getSellOrderPriceDate();
        $this->emptySellOrderPrice = $journalInfo['empty']->getSellOrderPrice();
        $this->emptySellOrderPriceDate = $journalInfo['empty']->getSellOrderPriceDate();
        $this->emptyBuyOrderPrice = $journalInfo['empty']->getBuyOrderPrice();
        $this->emptyBuyOrderPriceDate = $journalInfo['empty']->getBuyOrderPriceDate();

        $this->journalWeight = $this->getCalculatedJournalWeight($journalInfo['full']);
    }

    public function getAmountBooks(): int
    {
        return $this->amountBooks;
    }

    public function setAmountBooks(float $totalAmount): void
    {
        $this->amountBooks = (int)(($totalAmount * $this->fameAmount) / $this->fameToFill);
    }

    public function getFameToFill(): int
    {
        return $this->fameToFill;
    }

    public function getJournalName(): string
    {
        return $this->journalName;
    }

    public function getJournalWeight(): float
    {
        return $this->journalWeight;
    }

    public function getFullSellOrderPrice(): int
    {
        return $this->fullSellOrderPrice;
    }

    public function getFullSellOrderPriceDate(): DateTimeImmutable
    {
        return $this->fullSellOrderPriceDate;
    }

    public function getEmptySellOrderPrice(): int
    {
        return $this->emptySellOrderPrice;
    }

    public function getEmptySellOrderPriceDate(): DateTimeImmutable
    {
        return $this->emptySellOrderPriceDate;
    }

    public function getEmptyBuyOrderPrice(): int
    {
        return $this->emptyBuyOrderPrice;
    }

    public function getEmptyBuyOrderPriceDate(): DateTimeImmutable
    {
        return $this->emptyBuyOrderPriceDate;
    }

    public function getPrimaryBuyOrderPrice(): int
    {
        return $this->primaryBuyOrderPrice;
    }

    public function getPrimaryBuyOrderPriceDate(): DateTimeImmutable
    {
        return $this->primaryBuyOrderPriceDate;
    }

    public function getSecondaryBuyOrderPrice(): ?int
    {
        return $this->secondaryBuyOrderPrice;
    }

    public function getSecondaryBuyOrderPriceDate(): ?DateTimeImmutable
    {
        return $this->secondaryBuyOrderPriceDate;
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

    public function getSecondaryPriceAge(): ?int
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

    private function getJournalInfo(ItemEntity $itemEntity, array $journalData): array
    {
        $journalInfo = [];
        /** @var JournalEntity $journalEntity */
        foreach ($journalData as $journalEntity) {
            if ($journalEntity->getClass() === $itemEntity->getClass() &&
                (str_starts_with($itemEntity->getTier(), $journalEntity->getTier()))
                //Todo rename journal Entity to weaponGroup > class
            ) {
                if ($journalEntity->getFillStatus() === 'full') {
                    $journalInfo['full'] = $journalEntity;
                }
                if ($journalEntity->getFillStatus() === 'empty') {
                    $journalInfo['empty'] = $journalEntity;
                }
            }
        }
        return $journalInfo;
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

    private function setTierColor(): int
    {
        if (str_starts_with($this->tier, '2')) {
            return 2;
        }
        if (str_starts_with($this->tier, '3')) {
            return 3;
        }
        if (str_starts_with($this->tier, '4')) {
            return 4;
        }
        if (str_starts_with($this->tier, '5')) {
            return 5;
        }
        if (str_starts_with($this->tier, '6')) {
            return 6;
        }
        if (str_starts_with($this->tier, '7')) {
            return 7;
        }
        if (str_starts_with($this->tier, '8')) {
            return 8;
        }
        throw new \RuntimeException('No string found');
    }

    public function getTierColor(): int
    {
        return $this->tierColor;
    }

    public function getPrice()
    {
        if ($this->itemSellOrderPrice === 0) {
            return 'X';
        }
        return '0';
    }

    public function getRealName(): string
    {
        return $this->realName;
    }

    private function getCalculatedJournalWeight(JournalEntity $journal): float
    {
        return ($this->fameToFill / $this->fameAmount) * $journal->getWeight();
    }
}
