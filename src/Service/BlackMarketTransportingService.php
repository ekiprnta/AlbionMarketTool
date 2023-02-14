<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

use InvalidArgumentException;
use MZierdt\Albion\Entity\BlackMarketTransportEntity;
use MZierdt\Albion\repositories\ItemRepository;

class BlackMarketTransportingService
{
    public function __construct(
        private readonly ItemRepository $itemRepository,
        private readonly BlackMarketTransportingHelper $bmtHelper,
        private readonly ConfigService $configService,
    ) {
    }

    public function getDataForCity(string $itemCity, array $tierList): array
    {
        if (empty($tierList)) {
            throw new InvalidArgumentException('No Tiers selected');
        }
        if (empty($itemCity)) {
            throw new InvalidArgumentException('Please select a city');
        }
        $cityItem = $this->itemRepository->getItemsByLocationForBM($itemCity);
        $bmItems = $this->itemRepository->getItemsByLocationForBM('Black Market');
        $amountConfig = $this->configService->getBlackMarketSells();

        $bmtEntities = [];
        foreach ($bmItems as $bmItem) {
            $bmtEntities[] = new BlackMarketTransportEntity($bmItem);
        }

        /** @var BlackMarketTransportEntity $bmtEntity */
        foreach ($bmtEntities as $bmtEntity) {
            $bmtEntity->setCityItem($this->bmtHelper->calculateCityItem($bmtEntity->getBmItem(), $cityItem));
            $bmtEntity->setAmount(
                $this->bmtHelper->calculateAmount(
                    $bmtEntity->getCityItem()
                        ->getPrimaryResourceAmount(),
                    $bmtEntity->getCityItem()
                        ->getSecondaryResourceAmount(),
                    $amountConfig[$bmtEntity->getCityItem()->getTier()]
                )
            );
            $bmtEntity->setSingleProfit(
                $this->bmtHelper->calculateSingleProfit(
                    $bmtEntity->getBmItem()
                        ->getSellOrderPrice(),
                    $bmtEntity->getCityItem()
                        ->getSellOrderPrice()
                )
            );
            $bmtEntity->setProfit(
                $this->bmtHelper->calculateProfit($bmtEntity->getSingleProfit(), $bmtEntity->getAmount())
            );
            $bmtEntity->setProfitQuotient(
                $this->bmtHelper->calculateProfitQuotient($bmtEntity->getProfit(), $bmtEntity->getAmount())
            );
            $bmtEntity->setProfitGrade($this->bmtHelper->calculateProfitGrade($bmtEntity->getProfitQuotient()));
            $bmtEntity->setProfitPercentage(
                $this->bmtHelper->calculateProfitPercentage(
                    $bmtEntity->getBmItem()
                        ->getSellOrderPrice(),
                    $bmtEntity->getCityItem()
                        ->getSellOrderPrice()
                )
            );
            $bmtEntity->setTotalCost(
                $this->bmtHelper->calculateTotalCost(
                    $bmtEntity->getAmount(),
                    $bmtEntity->getCityItem()
                        ->getSellOrderPrice()
                )
            );
        }
//        $combinedItems = $this->combineItems($cityItems, $bmItems);
        return $this->filterItems($bmtEntities, $tierList);
    }

    private function filterItems(array $bmtEntities, array $tierList): array
    {
        /** @var BlackMarketTransportEntity $bmtEntity */
        foreach ($bmtEntities as $key => $bmtEntity) {
            if (! in_array((string) $bmtEntity->getBmItem()->getTier(), $tierList, true)) {
                unset($bmtEntities[$key]);
            }
        }
        return $bmtEntities;
    }
}
