<?php

namespace integration\Service;

use MZierdt\Albion\Entity\ResourceEntity;
use MZierdt\Albion\Entity\TransmutationEntity;
use MZierdt\Albion\repositories\RawResourceRepository;
use MZierdt\Albion\Service\ConfigService;
use MZierdt\Albion\Service\GlobalDiscountService;
use MZierdt\Albion\Service\TransmutationHelper;
use MZierdt\Albion\Service\TransmutationService;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class TransmutationServiceITest extends TestCase
{
    use ProphecyTrait;

    public function testGetTransmutationByCityException(): void
    {
        /** @var RawResourceRepository|ObjectProphecy $rawResourceRepository */
        $rawResourceRepository = $this->prophesize(RawResourceRepository::class);
        $transmutationHelper = new TransmutationHelper();
        /** @var ConfigService|ObjectProphecy $configService */
        $configService = $this->prophesize(ConfigService::class);
        /** @var GlobalDiscountService|ObjectProphecy $discountService */
        $discountService = $this->prophesize(GlobalDiscountService::class);

        $transmutationService = new TransmutationService(
            $rawResourceRepository->reveal(),
            $transmutationHelper,
            $configService->reveal(),
            $discountService->reveal(),
        );

        $rawResourceRepository->getRawResourcesByCity('Sterling')
            ->willReturn($this->getRawResources());
        $configService->getTransmutationWays()
            ->willReturn([
                '4to6' => ['5', '6'],
            ]);
        $configService->getTransmutationCost()
            ->willReturn(['5' => ['tier' => 1000], '6' => ['tier' => 2000]]);
        $discountService->getGlobalDiscount()
            ->willReturn(0.01);

        $testData = $transmutationService->getTransmutationByCity('Sterling');

        /** @var TransmutationEntity $transEntity */
        foreach ($testData as $transEntity) {
            $this->assertEquals(-1932.0, $transEntity->getProfit());
            $this->assertEquals('D', $transEntity->getProfitGrade());
            $this->assertEquals('4', $transEntity->getStartTierColor());
            $this->assertEquals('6', $transEntity->getEndTierColor());
            $this->assertEquals(2970.0, $transEntity->getTransmutePrice());
            $this->assertEquals('4to6', $transEntity->getPathName());
            $this->assertEquals([5, 6], $transEntity->getTransmutationPath());
        }
    }

    private function getRawResources(): array
    {
        $resourceA = new ResourceEntity([
            'tier' => '4',
            'name' => 'leather',
            'city' => 'Sterling',
            'realName' => 'fiber',
            'sellOrderPrice' => '61',
            'sellOrderPriceDate' => '2022-02-08 10:45:00',
            'buyOrderPrice' => '58',
            'buyOrderPriceDate' => '2022-01-08 10:45:00',
            'bonusCity' => 'Lymhurst',
            'amountInStorage' => null,
        ]);
        $resourceB = new ResourceEntity([
            'tier' => '6',
            'name' => 'leather',
            'city' => 'Sterling',
            'realName' => 'fiber',
            'sellOrderPrice' => '1099',
            'sellOrderPriceDate' => '2022-02-08 10:45:00',
            'buyOrderPrice' => '1098',
            'buyOrderPriceDate' => '2022-01-08 10:45:00',
            'bonusCity' => 'Lymhurst',
            'amountInStorage' => null,
        ]);
        $resourceC = new ResourceEntity([
            'tier' => '4',
            'name' => 'leather',
            'city' => 'Sterling',
            'realName' => 'ore',
            'sellOrderPrice' => '61',
            'sellOrderPriceDate' => '2022-02-08 10:45:00',
            'buyOrderPrice' => '58',
            'buyOrderPriceDate' => '2022-01-08 10:45:00',
            'bonusCity' => 'Lymhurst',
            'amountInStorage' => null,
        ]);
        $resourceD = new ResourceEntity([
            'tier' => '6',
            'name' => 'leather',
            'city' => 'Sterling',
            'realName' => 'ore',
            'sellOrderPrice' => '1099',
            'sellOrderPriceDate' => '2022-02-08 10:45:00',
            'buyOrderPrice' => '1098',
            'buyOrderPriceDate' => '2022-01-08 10:45:00',
            'bonusCity' => 'Lymhurst',
            'amountInStorage' => null,
        ]);
        $resourceE = new ResourceEntity([
            'tier' => '4',
            'name' => 'leather',
            'city' => 'Sterling',
            'realName' => 'hide',
            'sellOrderPrice' => '61',
            'sellOrderPriceDate' => '2022-02-08 10:45:00',
            'buyOrderPrice' => '58',
            'buyOrderPriceDate' => '2022-01-08 10:45:00',
            'bonusCity' => 'Lymhurst',
            'amountInStorage' => null,
        ]);
        $resourceF = new ResourceEntity([
            'tier' => '6',
            'name' => 'leather',
            'city' => 'Sterling',
            'realName' => 'hide',
            'sellOrderPrice' => '1099',
            'sellOrderPriceDate' => '2022-02-08 10:45:00',
            'buyOrderPrice' => '1098',
            'buyOrderPriceDate' => '2022-01-08 10:45:00',
            'bonusCity' => 'Lymhurst',
            'amountInStorage' => null,
        ]);
        $resourceG = new ResourceEntity([
            'tier' => '4',
            'name' => 'leather',
            'city' => 'Sterling',
            'realName' => 'wood',
            'sellOrderPrice' => '61',
            'sellOrderPriceDate' => '2022-02-08 10:45:00',
            'buyOrderPrice' => '58',
            'buyOrderPriceDate' => '2022-01-08 10:45:00',
            'bonusCity' => 'Lymhurst',
            'amountInStorage' => null,
        ]);
        $resourceH = new ResourceEntity([
            'tier' => '6',
            'name' => 'leather',
            'city' => 'Sterling',
            'realName' => 'wood',
            'sellOrderPrice' => '1099',
            'sellOrderPriceDate' => '2022-02-08 10:45:00',
            'buyOrderPrice' => '1098',
            'buyOrderPriceDate' => '2022-01-08 10:45:00',
            'bonusCity' => 'Lymhurst',
            'amountInStorage' => null,
        ]);

        return [
            $resourceA,
            $resourceB,
            $resourceC,
            $resourceD,
            $resourceE,
            $resourceF,
            $resourceG,
            $resourceH,
        ];
    }
}
