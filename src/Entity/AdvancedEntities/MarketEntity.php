<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity\AdvancedEntities;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\MappedSuperclass;

#[MappedSuperclass]
class MarketEntity
{
    #[Column(type: 'integer')]
    #[Id, GeneratedValue(strategy: 'AUTO')]
    protected ?int $id = null;
    #[Column(type: 'integer', nullable: true)]
    protected ?int $tierColor = null;
    #[Column(type: 'integer', nullable: true)]
    protected ?int $amount;
    #[Column(type: 'string', nullable: true)]
    protected ?string $city;

    #[Column(type: 'float', nullable: true)]
    protected ?float $materialCostBuy = null;
    #[Column(type: 'float', nullable: true)]
    protected ?float $profitBuy = null;
    #[Column(type: 'float', nullable: true)]
    protected ?float $profitPercentageBuy = null;
    #[Column(type: 'string', nullable: true)]
    protected ?string $profitGradeBuy = null;

    #[Column(type: 'float', nullable: true)]
    protected ?float $materialCostSell = null;
    #[Column(type: 'float', nullable: true)]
    protected ?float $profitSell = null;
    #[Column(type: 'float', nullable: true)]
    protected ?float $profitPercentageSell = null;
    #[Column(type: 'string', nullable: true)]
    protected ?string $profitGradeSell = null;

    #[Column(type: 'boolean')]
    protected bool $complete = false;

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    public function getMaterialCostSell(): ?float
    {
        return $this->materialCostSell;
    }

    public function setMaterialCostSell(?float $materialCostSell): void
    {
        $this->materialCostSell = $materialCostSell;
    }

    public function getProfitSell(): ?float
    {
        return $this->profitSell;
    }

    public function setProfitSell(?float $profitSell): void
    {
        $this->profitSell = $profitSell;
    }

    public function getProfitPercentageSell(): ?float
    {
        return $this->profitPercentageSell;
    }

    public function setProfitPercentageSell(?float $profitPercentageSell): void
    {
        $this->profitPercentageSell = $profitPercentageSell;
    }

    public function getProfitGradeSell(): ?string
    {
        return $this->profitGradeSell;
    }

    public function setProfitGradeSell(?string $profitGradeSell): void
    {
        $this->profitGradeSell = $profitGradeSell;
    }

    public function isComplete(): bool
    {
        return $this->complete;
    }

    public function setComplete(bool $complete): void
    {
        $this->complete = $complete;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getMaterialCostBuy(): ?float
    {
        return $this->materialCostBuy;
    }

    public function setMaterialCostBuy(?float $materialCostBuy): void
    {
        $this->materialCostBuy = $materialCostBuy;
    }

    public function getProfitBuy(): ?float
    {
        return $this->profitBuy;
    }

    public function setProfitBuy(?float $profitBuy): void
    {
        $this->profitBuy = $profitBuy;
    }

    public function getProfitPercentageBuy(): ?float
    {
        return $this->profitPercentageBuy;
    }

    public function setProfitPercentageBuy(?float $profitPercentageBuy): void
    {
        $this->profitPercentageBuy = $profitPercentageBuy;
    }

    public function getProfitGradeBuy(): ?string
    {
        return $this->profitGradeBuy;
    }

    public function setProfitGradeBuy(?string $profitGradeBuy): void
    {
        $this->profitGradeBuy = $profitGradeBuy;
    }

    public function getTierColor(): ?int
    {
        return $this->tierColor;
    }

    public function setTierColor(?int $tierColor): void
    {
        $this->tierColor = $tierColor;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(?int $amount): void
    {
        $this->amount = $amount;
    }
}