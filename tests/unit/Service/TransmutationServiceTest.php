<?php

namespace unit\Service;

use MZierdt\Albion\repositories\ResourceRepository;
use MZierdt\Albion\Service\ConfigService;
use MZierdt\Albion\Service\GlobalDiscountService;
use MZierdt\Albion\Service\TransmutationHelper;
use MZierdt\Albion\Service\TransmutationService;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class TransmutationServiceTest extends TestCase
{
    use ProphecyTrait;

    public function testGetTransmutationByCityException(): void
    {
        $resourceRepository = $this->prophesize(ResourceRepository::class);
        $transmutationHelper = $this->prophesize(TransmutationHelper::class);
        $configService = $this->prophesize(ConfigService::class);
        $discountService = $this->prophesize(GlobalDiscountService::class);

        $transmutationService = new TransmutationService(
            $resourceRepository->reveal(),
            $transmutationHelper->reveal(),
            $configService->reveal(),
            $discountService->reveal(),
        );

        $this->expectException(\InvalidArgumentException::class);

        $transmutationService->getTransmutationByCity('');
    }
}
