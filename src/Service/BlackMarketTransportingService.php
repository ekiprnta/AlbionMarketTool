<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

use InvalidArgumentException;
use MZierdt\Albion\Entity\BlackMarketTransportEntity;
use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\repositories\ItemRepository;

class BlackMarketTransportingService
{
    private int $maxWeight;

    public function __construct(private ItemRepository $itemRepository)
    {
    }

    public function getDataForCity(string $itemCity, int $weight)
    {
        if (empty($itemCity)) {
            throw new InvalidArgumentException('Please select a city');
        }
        if (empty($weight)) {
            throw new InvalidArgumentException('Please insert your maximum carry Weight');
        }
        $this->maxWeight = $weight;
        $items = $this->itemRepository->getItemsForTransport($itemCity);
        $combinedItems = $this->combineItems($items);
        ksort($combinedItems);
        /** @var BlackMarketTransportEntity $combinedItem */
        foreach ($combinedItems as $combinedItem) {
            $this->calculateAmount($combinedItem);
            $this->calculateProfit($combinedItem);
        }
        return $combinedItems;
    }

    private function combineItems(array $items): array
    {
        $listOfItemTransports = [];
        foreach ($items as $item) {
            if ($item->getCity() === 'Black Market') {
                $listOfItemTransports[$item->getName() . '#' . $item->getTier()] = new BlackMarketTransportEntity(
                    $item
                );
            }
        }
        $listOfItems = [];
        /** @var ItemEntity $item */
        foreach ($items as $item) {
            $tierName = $item->getName() . '#' . $item->getTier();
            $transportItem = $listOfItemTransports[$tierName];
            if ($item->getCity() === 'Fort Sterling') {
                $transportItem->setFsPrice($item->getSellOrderPrice());
                $transportItem->setFsPriceDate($item->getSellOrderPriceDate());
                $listOfItems[$tierName] = $transportItem;
            }
            if ($item->getCity() === 'Lymhurst') {
                $transportItem->setLymPrice($item->getSellOrderPrice());
                $transportItem->setLymPriceDate($item->getSellOrderPriceDate());
                $listOfItems[$tierName] = $transportItem;
            }
            if ($item->getCity() === 'Bridgewatch') {
                $transportItem->setBwPrice($item->getSellOrderPrice());
                $transportItem->setBwPriceDate($item->getSellOrderPriceDate());
                $listOfItems[$tierName] = $transportItem;
            }
            if ($item->getCity() === 'Martlock') {
                $transportItem->setMlPrice($item->getSellOrderPrice());
                $transportItem->setMlPriceDate($item->getSellOrderPriceDate());
                $listOfItems[$tierName] = $transportItem;
            }
            if ($item->getCity() === 'Thetford') {
                $transportItem->setThetPrice($item->getSellOrderPrice());
                $transportItem->setThetPriceDate($item->getSellOrderPriceDate());
                $listOfItems[$tierName] = $transportItem;
            }
        }
        return $listOfItems;
    }

    private function calculateAmount(BlackMarketTransportEntity $combinedItem): void
    {
        $combinedItem->setAmount((int)ceil($this->maxWeight / $combinedItem->getWeight()));
    }

    private function calculateProfit(BlackMarketTransportEntity $combinedItem): void
    {
        $bmPrice = $combinedItem->getBmPrice();
        $weight = $combinedItem->getWeight();
        if ($combinedItem->getFsPrice() !== null) {
            $combinedItem->setFsProfit((int)$bmPrice - $combinedItem->getFsPrice());
            $combinedItem->setFsWeightProfitQuotient((float)$combinedItem->getFsProfit() / $weight);
        }
        if ($combinedItem->getLymPrice() !== null) {
            $combinedItem->setLymProfit((int)$bmPrice - $combinedItem->getLymPrice());
            $combinedItem->setLymWeightProfitQuotient((float)$combinedItem->getLymProfit() / $weight);
        }
        if ($combinedItem->getBmPrice() !== null) {
            $combinedItem->setBwProfit((int)$bmPrice - $combinedItem->getBwPrice());
            $combinedItem->setBwWeightProfitQuotient((float)$combinedItem->getBwProfit() / $weight);
        }
        if ($combinedItem->getMlPrice() !== null) {
            $combinedItem->setMlProfit((int)$bmPrice - $combinedItem->getMlPrice());
            $combinedItem->setMlWeightProfitQuotient((float)$combinedItem->getMlProfit() / $weight);
        }
        if ($combinedItem->getThetPrice() !== null) {
            $combinedItem->setThetProfit((int)$bmPrice - $combinedItem->getThetPrice());
            $combinedItem->setThetWeightProfitQuotient((float)$combinedItem->getThetProfit() / $weight);
        }
    }
}
