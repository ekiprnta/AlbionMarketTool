<?php

declare(strict_types=1);

namespace integration\Service;

use MZierdt\Albion\Entity\EnchantingEntity;
use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\MaterialEntity;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\repositories\MaterialRepository;
use MZierdt\Albion\Service\EnchantingHelper;
use MZierdt\Albion\Service\EnchantingService;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class EnchantingServiceITest extends TestCase
{
    use ProphecyTrait;

    public function testGetEnchantingForCity()
    {
        /** @var MaterialRepository|ObjectProphecy $materialRepository */
        $materialRepository = $this->prophesize(MaterialRepository::class);
        /** @var ItemRepository|ObjectProphecy $itemRepository */
        $itemRepository = $this->prophesize(ItemRepository::class);

        $enchantingService = new EnchantingService(
            $materialRepository->reveal(),
            $itemRepository->reveal(),
            new EnchantingHelper()
        );

        $itemRepository->getItemsByLocation('TestCity')
            ->willReturn($this->getItems());
        $itemRepository->getItemsByLocationForBM('Black Market')
            ->willReturn($this->getItems());
        $materialRepository->getMaterialsByLocation('TestCity')
            ->willReturn($this->getMaterials());

        $result = $enchantingService->getEnchantingForCity('TestCity');
        /** @var EnchantingEntity $enchantingEntity */
        $enchantingEntity = $result[0];

        $this->assertEquals(2, $enchantingEntity->getBaseEnchantment());
        $this->assertEquals(144, $enchantingEntity->getMaterialAmount());
        $this->assertEquals(702.0, $enchantingEntity->getMaterialCost());
        $this->assertEquals(798.0, $enchantingEntity->getProfit());
        $this->assertEquals(136.18, $enchantingEntity->getProfitPercentage());
        $this->assertEquals('B', $enchantingEntity->getProfitGrade());
        $this->assertEquals(7, $enchantingEntity->getTierColor());
    }

    public function getItems(): array
    {
        $itemA = (new ItemEntity())->setTier(72)
            ->setName('2h_axe')
            ->setSellOrderPrice(1500)
            ->setPrimaryResourceAmount(16)
            ->setSecondaryResourceAmount(8);
        $itemB = (new ItemEntity())->setTier(73)
            ->setName('2h_axe')
            ->setSellOrderPrice(3000)
            ->setPrimaryResourceAmount(16)
            ->setSecondaryResourceAmount(8);

        return [$itemA, $itemB];
    }

    public function getMaterials(): array
    {
        return [(new MaterialEntity())->setName('relic')->setTier(70)->setBuyOrderPrice(5)];
    }
}
