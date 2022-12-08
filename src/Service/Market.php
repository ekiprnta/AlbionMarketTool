<?php

namespace MZierdt\Albion\Service;

class Market
{
    public const NUTRITION_FACTOR = 0.1125;
    public const MARKET_SETUP = 0.025;
    public const MARKET_FEE = 0.04;

    public const PREMIUM_FACTOR = 1.5;

    public const RRR_BASE_PERCENTAGE = 100;

    protected function calculateSell(float|int $price)
    {
        return $price * (1 - self::MARKET_FEE);
    }

    protected function calculateSellOrder(float|int $price)
    {
        return $price * (1 - self::MARKET_FEE - self::MARKET_SETUP);
    }

    protected function calculateBuyOrder(float|int $price)
    {
        return $price * (1 - self::MARKET_SETUP);
    }

}