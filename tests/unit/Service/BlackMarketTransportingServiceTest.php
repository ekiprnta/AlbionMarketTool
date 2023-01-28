<?php

namespace unit\Service;

use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\Service\BlackMarketTransportingHelper;
use MZierdt\Albion\Service\BlackMarketTransportingService;
use MZierdt\Albion\Service\ConfigService;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class BlackMarketTransportingServiceTest extends TestCase
{
    use ProphecyTrait;

    private BlackMarketTransportingService $bmtService;
    private ObjectProphecy|ItemRepository $itemRepository;
    private ObjectProphecy|BlackMarketTransportingHelper $bmtHelper;
    private ObjectProphecy|ConfigService $configService;

    protected function setUp(): void
    {
        $this->itemRepository = $this->prophesize(ItemRepository::class);
        $this->bmtHelper = $this->prophesize(BlackMarketTransportingHelper::class);
        $this->configService = $this->prophesize(ConfigService::class);

        $this->bmtService = new BlackMarketTransportingService(
            $this->itemRepository->reveal(),
            $this->bmtHelper->reveal(),
            $this->configService->reveal()
        );
    }

    /**
     * @dataProvider exceptionData
     */
    public function testGetDataForCityException(string $itemCity, array $tierList): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->bmtService->getDataForCity($itemCity, $tierList);
    }

    public function exceptionData(): array
    {
        return [['', ['1', '2']], ['a', []]];
    }
}
