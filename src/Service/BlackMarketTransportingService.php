<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

use InvalidArgumentException;
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
        dd($items);
    }
}