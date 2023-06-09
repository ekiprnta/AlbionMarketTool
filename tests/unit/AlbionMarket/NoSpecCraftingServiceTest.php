<?php

declare(strict_types=1);

namespace unit\AlbionMarket;

use MZierdt\Albion\AlbionMarket\NoSpecCraftingService;
use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\MaterialEntity;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class NoSpecCraftingServiceTest extends TestCase
{
    use ProphecyTrait;

    private NoSpecCraftingService $noSpecCraftingService;

    /**
     * @dataProvider provideDefaultCape
     */
    public function testCalculateDefaultCape(ItemEntity $defaultCape, int $tier, string $name): void
    {
        $data = $this->getTestDataItemEntity();

        $this->assertEquals($defaultCape, $this->noSpecCraftingService->calculateDefaultItem($tier, $name, $data));
    }

    private function getTestDataItemEntity(): array
    {
        return [(new ItemEntity())->setTier(41)->setName('cape'), (new ItemEntity())->setTier(52)->setName('robe')];
    }

    public function provideDefaultCape(): array
    {
        return [
            [(new ItemEntity())->setTier(41)->setName('cape'), 41, 'cape'],
            [(new ItemEntity())->setTier(52)->setName('robe'), 52, 'robe'],
        ];
    }

    public function testCalculateDefaultCapeException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->noSpecCraftingService->calculateDefaultItem(1, 'cape', []);
    }

    /**
     * @dataProvider provideSecondResource
     */
    public function testCalculateSecondResource(MaterialEntity $defaultCape, string $resourceName, int $tier): void
    {
        $heartsAndSigils = $this->getTestDataMaterialEntity();

        $this->assertEquals(
            $defaultCape,
            $this->noSpecCraftingService->calculateSecondResource($resourceName, $tier, $heartsAndSigils)
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
        $this->noSpecCraftingService->calculateSecondResource('test', 1, []);
    }

    /**
     * @dataProvider provideArtifact
     */
    public function testCalculateArtifact(?MaterialEntity $defaultCape, ?string $resourceName, int $tier): void
    {
        $data = $this->getTestDataMaterialEntity();
        $this->assertEquals($defaultCape, $this->noSpecCraftingService->calculateArtifact($resourceName, $tier, $data));
    }

    public function provideArtifact(): array
    {
        return [
            [(new MaterialEntity())->setTier(10)->setRealName('materialA')->setName('materialA'), 'materialA', 10],
            [(new MaterialEntity())->setTier(70)->setRealName('materialB')->setName('materialB'), 'materialB', 72],
            [null, 'materialC', 70],
            [null, null, 70],
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
            $this->noSpecCraftingService->calculateMaterialCost(
                $primaryItemCost,
                $secondaryMaterialCost,
                $secondaryMaterialAmount,
                $artifactCost
            )
        );
    }

    public function provideMaterialCost(): array
    {
        return [[1053, 50, 100, 10, 3], [180, 100, 3, 10, 50], [603, 3, 10, 50, 100]];
    }

    /**
     * @dataProvider provideProfit
     */
    public function testCalculateProfit(float $profit, int $specialCapePrice, int $materialCost): void
    {
        $this->assertEquals($profit, $this->noSpecCraftingService->calculateProfit($specialCapePrice, $materialCost));
    }

    public function provideProfit(): array
    {
        return [[68.5, 100, 25], [-29.875, 75, 100]];
    }

    protected function setUp(): void
    {
        $this->noSpecCraftingService = new NoSpecCraftingService();
    }
}
