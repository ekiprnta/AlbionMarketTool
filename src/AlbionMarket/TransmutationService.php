<?php

namespace MZierdt\Albion\AlbionMarket;

use InvalidArgumentException;
use MZierdt\Albion\Entity\AdvancedEntities\TransmutationEntity;
use MZierdt\Albion\repositories\ResourceRepository;
use MZierdt\Albion\Service\ConfigService;
use MZierdt\Albion\Service\GlobalDiscountService;

class TransmutationService
{
    public function __construct(
        private readonly ResourceRepository $resourceRepository,
        private readonly TransmutationHelper $transmutationHelper,
        private readonly ConfigService $configService,
        private readonly GlobalDiscountService $discountService,
    ) {
    }

    public function getTransmutationByCity(string $city): array
    {
        if (empty($city)) {
            throw new InvalidArgumentException('Please select a city');
        }

        $resources = $this->resourceRepository->getRawResourcesByCity($city);

        $transmutationWays = $this->configService->getTransmutationWays();
        $transmutationCost = $this->configService->getTransmutationCost();
        $globalDiscount = $this->discountService->getGlobalDiscount();

        $transmutationEntityList = [];
        foreach ($transmutationWays as $pathName => $transmutationWay) {
            $transmutationEntityList[] = new TransmutationEntity($pathName, $transmutationWay, 'fiber');
        }
        foreach ($transmutationWays as $pathName => $transmutationWay) {
            $transmutationEntityList[] = new TransmutationEntity($pathName, $transmutationWay, 'wood');
        }
        foreach ($transmutationWays as $pathName => $transmutationWay) {
            $transmutationEntityList[] = new TransmutationEntity($pathName, $transmutationWay, 'hide');
        }
        foreach ($transmutationWays as $pathName => $transmutationWay) {
            $transmutationEntityList[] = new TransmutationEntity($pathName, $transmutationWay, 'ore');
        }
        /** @var TransmutationEntity $transEntity */
        foreach ($transmutationEntityList as $transEntity) {
            [$startTier, $endTier] = $this->transmutationHelper->calculateStartAndEnd($transEntity->getPathName());
            $transEntity->setStartResource(
                $this->transmutationHelper->calculateResource($resources, $startTier, $transEntity->getResourceType())
            );
            $transEntity->setEndResource(
                $this->transmutationHelper->calculateResource($resources, $endTier, $transEntity->getResourceType())
            );
            $transEntity->setTransmutePrice(
                $this->transmutationHelper->calculateTransmutationPrice(
                    $transEntity->getTransmutationPath(),
                    $startTier,
                    $transmutationCost,
                    $globalDiscount
                )
            );

            $transEntity->setProfit(
                $this->transmutationHelper->calculateProfit(
                    $transEntity->getStartResource()
                        ->getSellOrderPrice(),
                    $transEntity->getEndResource()
                        ->getSellOrderPrice(),
                    $transEntity->getTransmutePrice()
                )
            );
            $transEntity->setProfitGrade($this->transmutationHelper->calculateProfitGrade($transEntity->getProfit()));
            $transEntity->setStartTierColor((int) ($transEntity->getStartResource()->getTier() / 10));
            $transEntity->setEndTierColor((int) ($transEntity->getEndResource()->getTier() / 10));
        }

        return $transmutationEntityList;
    }
}
