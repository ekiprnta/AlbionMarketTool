<?php

declare(strict_types=1);

namespace integration\Service;

use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\MaterialEntity;
use MZierdt\Albion\Entity\NoSpecEntity;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\repositories\MaterialRepository;
use MZierdt\Albion\Service\CapesCraftingHelper;
use MZierdt\Albion\Service\CapesCraftingService;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class CapesCraftingServiceITest extends TestCase
{
    use ProphecyTrait;

    private CapesCraftingService $capesCraftingService;
    private ItemRepository|ObjectProphecy $itemRepository;
    private MaterialRepository|ObjectProphecy $materialRepository;

    public function testGetCapesByCity(): void
    {
        $this->itemRepository->getArtifactCapesByCity('TestCity')
            ->willReturn($this->getArtifactCapes());
        $this->itemRepository->getDefaultCapesByCity('TestCity')
            ->willReturn($this->getCapes());

        $this->materialRepository->getHeartsAndSigilsByCity('TestCity')
            ->willReturn($this->getHearts());
        $this->materialRepository->getCapeArtifactsByCity('TestCity')
            ->willReturn($this->getArtifacts());

        $testData = $this->capesCraftingService->getCapesByCity('TestCity');

        /** @var NoSpecEntity $noSpecEntity */
        $noSpecEntity = $testData[0];

        $this->assertEquals(243.75, $noSpecEntity->getMaterialCost());
        $this->assertEquals(-93.75, $noSpecEntity->getProfit());
        $this->assertEquals(-93.75, $noSpecEntity->getProfitQuotient());
        $this->assertEquals('D', $noSpecEntity->getProfitGrade());
        $this->assertEquals(4, $noSpecEntity->getTierColor());
    }

    private function getArtifactCapes(): array
    {
        return [
            (new ItemEntity())->setTier(42)
                ->setSecondaryResource('heart')
                ->setSecondaryResourceAmount(10)
                ->setArtifact('capeArtifact')
                ->setSellOrderPrice(150),
        ];
    }

    private function getCapes(): array
    {
        return [(new ItemEntity())->setTier((42))->setBuyOrderPrice(200)];
    }

    private function getHearts(): array
    {
        return [(new MaterialEntity())->setRealName('heart')->setTier(10)->setBuyOrderPrice(100)];
    }

    private function getArtifacts(): array
    {
        return [(new MaterialEntity())->setTier(40)->setName('capeArtifact')->setBuyOrderPrice(50)];
    }

    protected function setUp(): void
    {
        $this->itemRepository = $this->prophesize(ItemRepository::class);
        $this->materialRepository = $this->prophesize(MaterialRepository::class);

        $this->capesCraftingService = new CapesCraftingService(
            $this->itemRepository->reveal(),
            $this->materialRepository->reveal(),
            new CapesCraftingHelper()
        );
    }
}
