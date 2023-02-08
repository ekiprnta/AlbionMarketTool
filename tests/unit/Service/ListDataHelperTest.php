<?php

declare(strict_types=1);

namespace unit\Service;

use MZierdt\Albion\Entity\ListDataEntity;
use MZierdt\Albion\Entity\ResourceEntity;
use MZierdt\Albion\Service\ListDataHelper;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class ListDataHelperTest extends TestCase
{
    use ProphecyTrait;

    private ListDataHelper $listDataHelper;

    /**
     * @dataProvider provideItemObjects
     */
    public function testCalculateSameItemObject(
        ?ResourceEntity $result,
        ResourceEntity $startResource,
        array $resources
    ): void {
        $ldEntity = new ListDataEntity($startResource);
        $this->assertEquals($result, $this->listDataHelper->calculateSameItemObject($ldEntity, $resources));
    }

    public function provideItemObjects(): array
    {
        $resourceA = (new ResourceEntity())
            ->setTier(71)
            ->setName('metalBar')
            ->setCity('TestCity')
            ->setRealName('metalBar')
            ->setSellOrderPrice(13986)
            ->setBuyOrderPrice(12235);
        return [[$resourceA, $resourceA, [$resourceA]], [null, $resourceA, []]];
    }

    /**
     * @dataProvider provideCheapPrices
     */
    public function testCalculateCheapestCity(
        string $result,
        int $fsPrice,
        int $lymPrice,
        int $bwPrice,
        int $mlPrice,
        int $thPrice,
    ): void {
        $this->assertEquals(
            $result,
            $this->listDataHelper->calculateCheapestCity($fsPrice, $lymPrice, $bwPrice, $mlPrice, $thPrice)
        );
    }

    public function provideCheapPrices(): array
    {
        return [
            ['Fort Sterling', 1, 2, 3, 4, 5],
            ['Lymhurst', 1, 0, 3, 4, 5],
            ['Bridgewatch', 1, 2, 0, 4, 5],
            ['Martlock', 1, 2, 3, 0, 5],
            ['Thetford', 1, 2, 3, 4, 0],
        ];
    }

    /**
     * @dataProvider providePrices
     */
    public function testCalculateMOstExpensiveCity(
        string $result,
        int $fsPrice,
        int $lymPrice,
        int $bwPrice,
        int $mlPrice,
        int $thPrice,
    ): void {
        $this->assertEquals(
            $result,
            $this->listDataHelper->calculateMostExpensiveCity($fsPrice, $lymPrice, $bwPrice, $mlPrice, $thPrice)
        );
    }

    public function providePrices(): array
    {
        return [
            ['Fort Sterling', 10, 2, 3, 4, 5],
            ['Lymhurst', 1, 10, 3, 4, 5],
            ['Bridgewatch', 1, 2, 10, 4, 5],
            ['Martlock', 1, 2, 3, 10, 5],
            ['Thetford', 1, 2, 3, 4, 10],
        ];
    }

    protected function setUp(): void
    {
        $this->listDataHelper = new ListDataHelper();
    }
}
