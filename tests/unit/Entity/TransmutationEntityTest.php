<?php

namespace unit\Entity;

use MZierdt\Albion\Entity\ResourceEntity;
use MZierdt\Albion\Entity\TransmutationEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class TransmutationEntityTest extends TestCase
{
    use ProphecyTrait;

    private TransmutationEntity $transEntity;

    public function testGetPathToTransmute(): void
    {
        $this->transEntity->setPathToTransmute('pathToTransmute');
        $this->assertEquals('pathToTransmute', $this->transEntity->getPathToTransmute());
    }

    public function testGetProfit(): void
    {
        $this->transEntity->setProfit(100);
        $this->assertEquals(100, $this->transEntity->getProfit());
    }

    public function testGetProfitGrade(): void
    {
        $this->transEntity->setPRofitGrade('A');
        $this->assertEquals('A', $this->transEntity->getPRofitGrade());
    }

    public function testGetStartResource(): void
    {
        $this->assertEquals(104, $this->transEntity->getStartResource()->getSellOrderPrice());
        $this->assertEquals('4', $this->transEntity->getStartResource()->getTier());
        $this->assertEquals('Fort Sterling', $this->transEntity->getStartResource()->getCity());
    }

    public function testGetEndResource(): void
    {
        $this->assertEquals(446, $this->transEntity->getEndResource()->getSellOrderPrice());
        $this->assertEquals('42', $this->transEntity->getEndResource()->getTier());
        $this->assertEquals('Fort Sterling', $this->transEntity->getEndResource()->getCity());
    }

    public function testGetTransmutePrice(): void
    {
        $this->assertEquals(3951, $this->transEntity->getTransmutePrice());
    }

    public function testGetEndTierColor(): void
    {
        $this->assertEquals('4', $this->transEntity->getEndTierColor());
    }

    public function testGetStartTierColor(): void
    {
        $this->assertEquals('4', $this->transEntity->getStartTierColor());
    }

    protected function setUp(): void
    {
        $this->transEntity = new TransmutationEntity(
            new ResourceEntity([
                'tier' => '4',
                'name' => 'cloth',
                'city' => 'Fort Sterling',
                'realName' => 'cloth',
                'sellOrderPrice' => '104',
                'sellOrderPriceDate' => '2022-12-08 10:45:00',
                'buyOrderPrice' => '100',
                'buyOrderPriceDate' => '2022-12-08 10:45:00',
                'bonusCity' => 'Lymhurst',
                'amountInStorage' => null,
            ]), new ResourceEntity(
            [
                'tier' => '42',
                'name' => 'cloth',
                'city' => 'Fort Sterling',
                'realName' => 'cloth',
                'sellOrderPrice' => '446',
                'sellOrderPriceDate' => '2022-12-08 10:45:00',
                'buyOrderPrice' => '400',
                'buyOrderPriceDate' => '2022-12-08 10:45:00',
                'bonusCity' => 'Lymhurst',
                'amountInStorage' => null,
            ]
        ), 3951
        );
    }
}