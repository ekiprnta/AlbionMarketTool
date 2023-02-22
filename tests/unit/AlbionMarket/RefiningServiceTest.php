<?php

namespace unit\AlbionMarket;

use MZierdt\Albion\AlbionMarket\RefiningService;
use MZierdt\Albion\AlbionMarket\RefiningService;
use MZierdt\Albion\repositories\ResourceRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class RefiningServiceTest extends TestCase
{
    use ProphecyTrait;

    private RefiningService $refiningService;
    private ObjectProphecy|ResourceRepository $resourceRepository;
    private ObjectProphecy|RefiningService $refiningHelper;

    protected function setUp(): void
    {
        $this->resourceRepository = $this->prophesize(ResourceRepository::class);
        $this->refiningHelper = $this->prophesize(RefiningService::class);

        $this->refiningService = new RefiningService(
            $this->resourceRepository->reveal(),
            $this->refiningHelper->reveal(),
        );
    }

    public function testGetRefiningRates(): void
    {
        $this->assertEquals([
            'No City Bonus No Focus' => 15.2,
            'No City Bonus Focus' => 43.5,
            'City Bonus No Focus' => 36.7,
            'City Bonus Focus' => 53.9,
        ], $this->refiningService->getRefiningRates());
    }

    /**
     * @dataProvider getExceptionValues
     */
    public function testGetDataForCityException(string $itemCity): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->refiningService->getRefiningForCity($itemCity, 10);
    }

    public function getExceptionValues(): array
    {
        return [['']];
    }
}
