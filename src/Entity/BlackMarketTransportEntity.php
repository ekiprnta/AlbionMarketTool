<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity;

use DateTimeImmutable;

class BlackMarketTransportEntity
{
    private ItemEntity $bmItem;

    private ItemEntity $fsItem;
    private ItemEntity $lymItem;
    private ItemEntity $bwItem;
    private ItemEntity $mlItem;
    private ItemEntity $thItem;

    private int $fsAmount;
    private float $fsProfit;
    private float $fsSingleProfit;
    private float $fsWeightProfitQuotient;
    private string $fsProfitGrade;

    private int $lymAmount;
    private float $lymProfit;
    private float $lymSingleProfit;
    private float $lymWeightProfitQuotient;
    private string $lymProfitGrade;

    private int $bwAmount;
    private float $bwProfit;
    private float $bwSingleProfit;
    private float $bwWeightProfitQuotient;
    private string $bwProfitGrade;

    private int $mlAmount;
    private float $mlProfit;
    private float $mlSingleProfit;
    private float $mlWeightProfitQuotient;
    private string $mlProfitGrade;

    private int $thAmount;
    private float $thProfit;
    private float $thSingleProfit;
    private float $thWeightProfitQuotient;
    private string $thProfitGrade;

    private string $tierColor;

    public function __construct(ItemEntity $bmItem, private int $weight)
    {
        $this->bmItem = $bmItem;
        $this->tierColor = $bmItem->getTier()[0];
    }

    public function getTierColor(): string
    {
        return $this->tierColor;
    }

    public function getFsProfitGrade(): string
    {
        return $this->fsProfitGrade;
    }

    public function setFsProfitGrade(string $fsProfitGrade): void
    {
        $this->fsProfitGrade = $fsProfitGrade;
    }

    public function getLymProfitGrade(): string
    {
        return $this->lymProfitGrade;
    }

    public function setLymProfitGrade(string $lymProfitGrade): void
    {
        $this->lymProfitGrade = $lymProfitGrade;
    }

    public function getBwProfitGrade(): string
    {
        return $this->bwProfitGrade;
    }

    public function setBwProfitGrade(string $bwProfitGrade): void
    {
        $this->bwProfitGrade = $bwProfitGrade;
    }

    public function getMlProfitGrade(): string
    {
        return $this->mlProfitGrade;
    }

    public function setMlProfitGrade(string $mlProfitGrade): void
    {
        $this->mlProfitGrade = $mlProfitGrade;
    }

    public function getThProfitGrade(): string
    {
        return $this->thProfitGrade;
    }

    public function setThProfitGrade(string $thProfitGrade): void
    {
        $this->thProfitGrade = $thProfitGrade;
    }

    public function getFsWeightProfitQuotient(): float
    {
        return $this->fsWeightProfitQuotient;
    }

    public function setFsWeightProfitQuotient(float $fsWeightProfitQuotient): void
    {
        $this->fsWeightProfitQuotient = $fsWeightProfitQuotient;
    }

    public function getLymWeightProfitQuotient(): float
    {
        return $this->lymWeightProfitQuotient;
    }

    public function setLymWeightProfitQuotient(float $lymWeightProfitQuotient): void
    {
        $this->lymWeightProfitQuotient = $lymWeightProfitQuotient;
    }

    public function getBwWeightProfitQuotient(): float
    {
        return $this->bwWeightProfitQuotient;
    }

    public function setBwWeightProfitQuotient(float $bwWeightProfitQuotient): void
    {
        $this->bwWeightProfitQuotient = $bwWeightProfitQuotient;
    }

    public function getMlWeightProfitQuotient(): float
    {
        return $this->mlWeightProfitQuotient;
    }

    public function setMlWeightProfitQuotient(float $mlWeightProfitQuotient): void
    {
        $this->mlWeightProfitQuotient = $mlWeightProfitQuotient;
    }

    public function getThWeightProfitQuotient(): float
    {
        return $this->thWeightProfitQuotient;
    }

    public function setThWeightProfitQuotient(float $thWeightProfitQuotient): void
    {
        $this->thWeightProfitQuotient = $thWeightProfitQuotient;
    }

    public function setFsProfit(array $profitArray): void
    {
        $this->fsAmount = $profitArray['amount'];
        $this->fsProfit = $profitArray['profit'];
        $this->fsSingleProfit = $profitArray['singleProfit'];
    }

    public function setLymProfit(array $profitArray): void
    {
        $this->lymAmount = $profitArray['amount'];
        $this->lymProfit = $profitArray['profit'];
        $this->lymSingleProfit = $profitArray['singleProfit'];
    }

    public function setBwProfit(array $profitArray): void
    {
        $this->bwAmount = $profitArray['amount'];
        $this->bwProfit = $profitArray['profit'];
        $this->bwSingleProfit = $profitArray['singleProfit'];
    }

    public function setMlProfit(array $profitArray): void
    {
        $this->mlAmount = $profitArray['amount'];
        $this->mlProfit = $profitArray['profit'];
        $this->mlSingleProfit = $profitArray['singleProfit'];
    }

    public function setThProfit(array $profitArray): void
    {
        $this->thAmount = $profitArray['amount'];
        $this->thProfit = $profitArray['profit'];
        $this->thSingleProfit = $profitArray['singleProfit'];
    }

    public function getFsAmount(): int
    {
        return $this->fsAmount;
    }

    public function getFsProfit(): float
    {
        return $this->fsProfit;
    }

    public function getFsSingleProfit(): float
    {
        return $this->fsSingleProfit;
    }

    public function getLymAmount(): int
    {
        return $this->lymAmount;
    }

    public function getLymProfit(): float
    {
        return $this->lymProfit;
    }

    public function getLymSingleProfit(): float
    {
        return $this->lymSingleProfit;
    }

    public function getBwAmount(): int
    {
        return $this->bwAmount;
    }

    public function getBwProfit(): float
    {
        return $this->bwProfit;
    }

    public function getBwSingleProfit(): float
    {
        return $this->bwSingleProfit;
    }

    public function getMlAmount(): int
    {
        return $this->mlAmount;
    }

    public function getMlProfit(): float
    {
        return $this->mlProfit;
    }

    public function getMlSingleProfit(): float
    {
        return $this->mlSingleProfit;
    }

    public function getThAmount(): int
    {
        return $this->thAmount;
    }

    public function getThProfit(): float
    {
        return $this->thProfit;
    }

    public function getThSingleProfit(): float
    {
        return $this->thSingleProfit;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function getFsItem(): ItemEntity
    {
        return $this->fsItem;
    }

    public function setFsItem(ItemEntity $fsItem): void
    {
        $this->fsItem = $fsItem;
    }

    public function getLymItem(): ItemEntity
    {
        return $this->lymItem;
    }

    public function setLymItem(ItemEntity $lymItem): void
    {
        $this->lymItem = $lymItem;
    }

    public function getBwItem(): ItemEntity
    {
        return $this->bwItem;
    }

    public function setBwItem(ItemEntity $bwItem): void
    {
        $this->bwItem = $bwItem;
    }

    public function getMlItem(): ItemEntity
    {
        return $this->mlItem;
    }

    public function setMlItem(ItemEntity $mlItem): void
    {
        $this->mlItem = $mlItem;
    }

    public function getThItem(): ItemEntity
    {
        return $this->thItem;
    }

    public function setThItem(ItemEntity $thItem): void
    {
        $this->thItem = $thItem;
    }

    public function getBmItem(): ItemEntity
    {
        return $this->bmItem;
    }
}
