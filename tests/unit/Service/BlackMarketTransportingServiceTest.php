<?php

namespace unit\Service;

use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\Service\BlackMarketTransportingHelper;
use MZierdt\Albion\Service\BlackMarketTransportingService;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class BlackMarketTransportingServiceTest extends TestCase
{
    use ProphecyTrait;

    private BlackMarketTransportingService $bmtService;
    private ObjectProphecy|ItemRepository $itemRepository;
    private ObjectProphecy|BlackMarketTransportingHelper $bmtHelper;

    protected function setUp(): void
    {
        $this->itemRepository = $this->prophesize(ItemRepository::class);
        $this->bmtHelper = $this->prophesize(BlackMarketTransportingHelper::class);

        $this->bmtService = new BlackMarketTransportingService(
            $this->itemRepository->reveal(),
            $this->bmtHelper->reveal(),
        );
    }

/** @dataProvider exceptionData */
    public function testGetDataForCityException(string $itemCity, int $weight, array $tierList): void
    {
        $this->expectException('InvalidArgumentException');

        $this->bmtService->getDataForCity($itemCity,$weight, $tierList);
    }

    public function exceptionData(): array
    {
        return [
            ['',5,['1','2']],
            ['a',0,['1','2']],
            ['a',5,[]],
        ];
    }
}