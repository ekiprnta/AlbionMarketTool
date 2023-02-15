<?php

declare(strict_types=1);

namespace unit\Service;

use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\MaterialEntity;
use MZierdt\Albion\Service\CapesCraftingHelper;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class CapesCraftingHelperTest extends TestCase
{
    use ProphecyTrait;

    private CapesCraftingHelper $ccHelper;

    /**
     * @dataProvider provideDefaultCape
     */
    public function testCalculateDefaultCape(ItemEntity $defaultCape, int $tier): void
    {
        $data = $this->getTestDataItemEntity();

        $this->assertEquals($defaultCape, $this->ccHelper->calculateDefaultItem($tier, $data));
    }

    private function getTestDataItemEntity(): array
    {
        return [(new ItemEntity())->setTier(41), (new ItemEntity())->setTier(52)];
    }

    public function provideDefaultCape(): array
    {
        return [[(new ItemEntity())->setTier(41), 41], [(new ItemEntity())->setTier(52), 52]];
    }

    public function testCalculateDefaultCapeException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->ccHelper->calculateDefaultItem(1, []);
    }

    /**
     * @dataProvider provideSecondResource
     */
    public function testCalculateSecondResource(MaterialEntity $defaultCape, string $resourceName, int $tier): void
    {
        $heartsAndSigils = $this->getTestDataMaterialEntity();

        $this->assertEquals(
            $defaultCape,
            $this->ccHelper->calculateSecondResource($resourceName, $tier, $heartsAndSigils)
        );
    }

    private function getTestDataMaterialEntity(): array
    {
        return [
            (new MaterialEntity())->setTier(10)
                ->setRealName('materialA')
                ->setName('materialA'),
            (new MaterialEntity())->setTier(70)
                ->setRealName('materialB')
                ->setName('materialB'),
        ];
    }

    public function provideSecondResource(): array
    {
        return [
            [(new MaterialEntity())->setTier(10)->setRealName('materialA')->setName('materialA'), 'materialA', 10],
            [(new MaterialEntity())->setTier(70)->setRealName('materialB')->setName('materialB'), 'materialB', 70],
        ];
    }

    public function testCalculateSecondResourceException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->ccHelper->calculateSecondResource('test', 1, []);
    }

    /**
     * @dataProvider provideArtifact
     */
    public function testCalculateArtifact(?MaterialEntity $defaultCape, string $resourceName, int $tier): void
    {
        $data = $this->getTestDataMaterialEntity();
        $this->assertEquals($defaultCape, $this->ccHelper->calculateArtifact($resourceName, $tier, $data));
    }

    public function provideArtifact(): array
    {
        return [
            [(new MaterialEntity())->setTier(10)->setRealName('materialA')->setName('materialA'), 'materialA', 10],
            [(new MaterialEntity())->setTier(70)->setRealName('materialB')->setName('materialB'), 'materialB', 72],
            [null, 'materialC', 70],
        ];
    }

    /**
     * @dataProvider provideMaterialCost
     */
    public function testCalculateMaterialCost(
        float $materialCost,
        int $primaryItemCost,
        int $secondaryMaterialCost,
        int $secondaryMaterialAmount,
        int $artifactCost
    ): void {
        $this->assertEquals(
            $materialCost,
            $this->ccHelper->calculateMaterialCost(
                $primaryItemCost,
                $secondaryMaterialCost,
                $secondaryMaterialAmount,
                $artifactCost
            )
        );
    }

    public function provideMaterialCost(): array
    {
        return [[1051.675, 50, 100, 10, 3], [176.25, 100, 3, 10, 50], [600.425, 3, 10, 50, 100]];
    }

    /**
     * @dataProvider provideProfit
     */
    public function testCalculateProfit(int $profit, int $specialCapePrice, int $materialCost): void
    {
        $this->assertEquals($profit, $this->ccHelper->calculateProfit($specialCapePrice, $materialCost));
    }

    public function provideProfit(): array
    {
        return [[75, 100, 25], [-25, 75, 100]];
    }

    protected function setUp(): void
    {
        $this->ccHelper = new CapesCraftingHelper();
    }
}
