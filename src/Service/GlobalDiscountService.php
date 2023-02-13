<?php

namespace MZierdt\Albion\Service;

use MZierdt\Albion\AlbionDataAPI\MiscApiService;

class GlobalDiscountService
{
    private const BASE_GOLD_PRICE = 5000;

    public function __construct(private readonly MiscApiService $miscApiService)
    {
    }

    public function getGlobalDiscount(): float
    {
        $goldPrice = $this->miscApiService->getGoldPrice();
        return 1 - $goldPrice / self::BASE_GOLD_PRICE;
    }
}
