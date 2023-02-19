<?php

namespace unit\Entity\AdvancedEntities;

use MZierdt\Albion\Entity\AdvancedEntities\TransmutationEntity;
use MZierdt\Albion\Entity\ResourceEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class TransmutationEntityTest extends TestCase
{
    use ProphecyTrait;

    private TransmutationEntity $transEntity;

    protected function setUp(): void
    {
        $this->transEntity = new TransmutationEntity('40to62', ['50', '60', '61', '62'], 'cloth');
    }

    public function testGetResourceType(): void
    {
        $this->assertEquals('cloth', $this->transEntity->getResourceType());
    }

    public function testGetStartTierColor(): void
    {
        $this->transEntity->setStartTierColor('4');
        $this->assertEquals('4', $this->transEntity->getStartTierColor());
    }

    public function testGetEndTierColor(): void
    {
        $this->transEntity->setEndTierColor('4');
        $this->assertEquals('4', $this->transEntity->getEndTierColor());
    }

    public function testGetTransmutePrice(): void
    {
        $this->transEntity->setTransmutePrice(55007.35);
        $this->assertEquals(55007.35, $this->transEntity->getTransmutePrice());
    }

    public function testGetProfit(): void
    {
        $this->transEntity->setProfit(120000);
        $this->assertEquals(120000, $this->transEntity->getProfit());
    }

    public function testGetProfitGrade(): void
    {
        $this->transEntity->setProfitGrade('A');
        $this->assertEquals('A', $this->transEntity->getProfitGrade());
    }

    public function testGetStartResource(): void
    {
        $this->transEntity->setStartResource(
            new ResourceEntity([
                'tier' => '20',
                'name' => 'cloth',
                'city' => 'Fort Sterling',
                'realName' => 'cloth',
                'sellOrderPrice' => '27',
                'sellOrderPriceDate' => '2022-12-08 10:45:00',
                'buyOrderPrice' => '26',
                'buyOrderPriceDate' => '2022-12-08 10:45:00',
                'bonusCity' => 'Lymhurst',
                'amountInStorage' => null,
            ])
        );
        $this->assertEquals(
            new ResourceEntity([
                'tier' => '20',
                'name' => 'cloth',
                'city' => 'Fort Sterling',
                'realName' => 'cloth',
                'sellOrderPrice' => '27',
                'sellOrderPriceDate' => '2022-12-08 10:45:00',
                'buyOrderPrice' => '26',
                'buyOrderPriceDate' => '2022-12-08 10:45:00',
                'bonusCity' => 'Lymhurst',
                'amountInStorage' => null,
            ]),
            $this->transEntity->getStartResource()
        );
    }

    public function testGetEndResource(): void
    {
        $this->transEntity->setEndResource(
            new ResourceEntity([
                'tier' => '20',
                'name' => 'cloth',
                'city' => 'Fort Sterling',
                'realName' => 'cloth',
                'sellOrderPrice' => '27',
                'sellOrderPriceDate' => '2022-12-08 10:45:00',
                'buyOrderPrice' => '26',
                'buyOrderPriceDate' => '2022-12-08 10:45:00',
                'bonusCity' => 'Lymhurst',
                'amountInStorage' => null,
            ])
        );
        $this->assertEquals(
            new ResourceEntity([
                'tier' => '20',
                'name' => 'cloth',
                'city' => 'Fort Sterling',
                'realName' => 'cloth',
                'sellOrderPrice' => '27',
                'sellOrderPriceDate' => '2022-12-08 10:45:00',
                'buyOrderPrice' => '26',
                'buyOrderPriceDate' => '2022-12-08 10:45:00',
                'bonusCity' => 'Lymhurst',
                'amountInStorage' => null,
            ]),
            $this->transEntity->getEndResource()
        );
    }

    public function testGetPathName(): void
    {
        $this->assertEquals('40to62', $this->transEntity->getPathName());
    }

    public function testGetTransmutationPath(): void
    {
        $this->assertEquals(['50', '60', '61', '62'], $this->transEntity->getTransmutationPath());
    }
}
