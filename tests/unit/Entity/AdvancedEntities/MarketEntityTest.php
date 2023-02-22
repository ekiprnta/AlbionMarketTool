<?php

declare(strict_types=1);

namespace unit\Entity\AdvancedEntities;

use MZierdt\Albion\Entity\AdvancedEntities\MarketEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class MarketEntityTest extends TestCase
{
    use ProphecyTrait;

    private MarketEntity $marketEntity;

    public function testGetId(): void
    {
        $this->marketEntity->setId(1);
        $this->assertEquals(1, $this->marketEntity->getId());
    }

    public function testGetTierColor(): void
    {
        $this->marketEntity->setTierColor(1);
        $this->assertEquals(1, $this->marketEntity->getTierColor());
    }

    public function testGetAmount(): void
    {
        $this->marketEntity->setAmount(1);
        $this->assertEquals(1, $this->marketEntity->getAmount());
    }

    public function testGetCity(): void
    {
        $this->marketEntity->setCity('city');
        $this->assertEquals('city', $this->marketEntity->getCity());
    }

    public function testGetMaterialCostBuy(): void
    {
        $this->marketEntity->setMaterialCostBuy(1);
        $this->assertEquals(1, $this->marketEntity->getMaterialCostBuy());
    }

    public function testGetProfitBuy(): void
    {
        $this->marketEntity->setProfitBuy(1);
        $this->assertEquals(1, $this->marketEntity->getProfitBuy());
    }

    public function testGetProfitPercentageBuy(): void
    {
        $this->marketEntity->setProfitPercentageBuy(1);
        $this->assertEquals(1, $this->marketEntity->getProfitPercentageBuy());
    }

    public function testGetProfitGradeBuy(): void
    {
        $this->marketEntity->setProfitGradeBuy('S');
        $this->assertEquals('S', $this->marketEntity->getProfitGradeBuy());
    }

    public function testGetMaterialCostSell(): void
    {
        $this->marketEntity->setMaterialCostSell(1);
        $this->assertEquals(1, $this->marketEntity->getMaterialCostSell());
    }

    public function testGetProfitSell(): void
    {
        $this->marketEntity->setProfitSell(1);
        $this->assertEquals(1, $this->marketEntity->getProfitSell());
    }

    public function testGetProfitPercentageSell(): void
    {
        $this->marketEntity->setProfitPercentageSell(1);
        $this->assertEquals(1, $this->marketEntity->getProfitPercentageSell());
    }

    public function testGetProfitGradeSell(): void
    {
        $this->marketEntity->setProfitGradeSell('S');
        $this->assertEquals('S', $this->marketEntity->getProfitGradeSell());
    }

    public function testIsComplete(): void
    {
        $this->marketEntity->setComplete(true);
        $this->assertEquals(true, $this->marketEntity->IsComplete());
    }

    protected function setUp(): void
    {
        $this->marketEntity = new MarketEntity();
    }
}
