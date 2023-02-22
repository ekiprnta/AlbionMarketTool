<?php

declare(strict_types=1);

namespace integration\AlbionMarket;

use MZierdt\Albion\AlbionMarket\NoSpecCraftingHelper;
use MZierdt\Albion\AlbionMarket\NoSpecCraftingService;
use MZierdt\Albion\Entity\AdvancedEntities\NoSpecEntity;
use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\MaterialEntity;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\repositories\MaterialRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class NoSpecCraftingServiceITest extends TestCase
{
    use ProphecyTrait;

    private NoSpecCraftingService $capesCraftingService;
    private ItemRepository|ObjectProphecy $itemRepository;
    private MaterialRepository|ObjectProphecy $materialRepository;

    public function testGetCapesByCity(): void
    {
        $this->itemRepository->getArtifactCapesByCity('TestCity')
            ->willReturn($this->getArtifactCapes());
        $this->itemRepository->getDefaultCapesByCity('TestCity')
            ->willReturn($this->getCapes());
        $this->itemRepository->getDefaultArmor('TestCity')
            ->willReturn([]);
        $this->itemRepository->getRoyalItemsByCity('TestCity')
            ->willReturn([]);

        $this->materialRepository->getHeartsAndSigilsByCity('TestCity')
            ->willReturn($this->getHearts());
        $this->materialRepository->getCapeArtifactsByCity('TestCity')
            ->willReturn($this->getArtifacts());

        $testData = $this->capesCraftingService->getCapesByCity('TestCity');

        /** @var NoSpecEntity $noSpecEntity */
        $noSpecEntity = $testData[0];

        $this->assertEquals(1250, $noSpecEntity->getMaterialCost());
        $this->assertEquals(-1109.75, $noSpecEntity->getProfit());
        $this->assertEquals(11.99, $noSpecEntity->getProfitPercentage());
        $this->assertEquals('D', $noSpecEntity->getProfitGrade());
        $this->assertEquals(4, $noSpecEntity->getTierColor());
    }

    private function getArtifactCapes(): array
    {
        return [
            (new ItemEntity())->setTier(42)
                ->setPrimaryResource('cape')
                ->setSecondaryResource('heart')
                ->setSecondaryResourceAmount(10)
                ->setArtifact('capeArtifact')
                ->setSellOrderPrice(150),
        ];
    }

    private function getCapes(): array
    {
        return [(new ItemEntity())->setTier((42))->setSellOrderPrice(200)->setName('cape')];
    }

    private function getHearts(): array
    {
        return [(new MaterialEntity())->setRealName('heart')->setTier(10)->setSellOrderPrice(100)];
    }

    private function getArtifacts(): array
    {
        return [(new MaterialEntity())->setTier(40)->setName('capeArtifact')->setSellOrderPrice(50)];
    }

    protected function setUp(): void
    {
        $this->itemRepository = $this->prophesize(ItemRepository::class);
        $this->materialRepository = $this->prophesize(MaterialRepository::class);

        $this->capesCraftingService = new NoSpecCraftingService(
            $this->itemRepository->reveal(),
            $this->materialRepository->reveal(),
            new NoSpecCraftingHelper()
        );
    }
}
