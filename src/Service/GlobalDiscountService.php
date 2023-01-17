<?php

namespace MZierdt\Albion\Service;

class GlobalDiscountService
{
    private const BASE_GOLD_PRICE = 5000;

    public function __construct(private readonly ApiService $apiService)
    {
    }

    public function getGlobalDiscount(): float
    {
        $goldPrice = $this->apiService->getGoldPrice();
        return (1 - $goldPrice / self::BASE_GOLD_PRICE);
    }

}