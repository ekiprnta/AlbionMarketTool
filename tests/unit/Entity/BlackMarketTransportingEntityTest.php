<?php

namespace unit\Entity;

use MZierdt\Albion\Entity\BlackMarketTransportEntity;
use MZierdt\Albion\Entity\ItemEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class BlackMarketTransportingEntityTest extends TestCase
{
    use ProphecyTrait;

    private BlackMarketTransportEntity $bmtEntity;

    protected function setUp(): void
    {
        $this->bmtEntity = new BlackMarketTransportEntity(
            new ItemEntity([
                'tier' => '41',
                'name' => 'ABC',
                'weaponGroup' => '123',
                'realName' => 'test',
                'class' => 'mage',
                'city' => 'city',
                'quality' => '2',
                'sellOrderPrice' => 5,
                'sellOrderPriceDate' => '2022-12-09 09:36:15',
                'buyOrderPrice' => 10,
                'buyOrderPriceDate' => '2022-12-09 09:36:15',
                'primaryResource' => 'planks',
                'primaryResourceAmount' => '20',
                'secondaryResource' => 'cloth',
                'secondaryResourceAmount' => '12',
                'bonusCity' => 'Fort Sterling',
                'fameFactor' => null,
                'amountInStorage' => null,
            ]),
            10
        );
    }

    public function testAmount(): void
    {
        $this->bmtEntity->setAmount(5);
        $this->assertEquals(5, $this->bmtEntity->getAmount());
    }

    public function testProfit(): void
    {
        $this->bmtEntity->setProfit(5.47);
        $this->assertEquals(5.47, $this->bmtEntity->getProfit());
    }

    public function testSingleProfit(): void
    {
        $this->bmtEntity->setSingleProfit(12.47);
        $this->assertEquals(12.47, $this->bmtEntity->getSingleProfit());
    }

    public function testWeightProfitQuotient(): void
    {
        $this->bmtEntity->setWeightProfitQuotient(456.47);
        $this->assertEquals(456.47, $this->bmtEntity->getWeightProfitQuotient());
    }

    public function testProfitGrade(): void
    {
        $this->bmtEntity->setProfitGrade('S');
        $this->assertEquals('S', $this->bmtEntity->getProfitGrade());
    }

    public function testTierColor(): void
    {
        $this->assertEquals('4', $this->bmtEntity->getTierColor());
    }

    public function testTotalCost(): void
    {
        $this->bmtEntity->setTotalCost(5);
        $this->assertEquals(5, $this->bmtEntity->getTotalCost());
    }

    public function testProfitPercentage(): void
    {
        $this->bmtEntity->setProfitPercentage(130.3);
        $this->assertEquals(130.3, $this->bmtEntity->getProfitPercentage());
    }
}
