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

    public function __construct(ItemEntity $bmItem, private int $weight)
    {
        $this->bmItem = $bmItem;
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
