<?php

namespace MZierdt\Albion\Service;

class Market
{
    public const NUTRITION_FACTOR = 0.1125;
    public const MARKET_SETUP = 0.025;
    public const MARKET_FEE = 0.04;

    public const PREMIUM_FACTOR = 1.5;

    public const RRR_BASE_PERCENTAGE = 100;

//    protected function calculateSell(float|int $price): float
//    {
//        return $price * (1 - self::MARKET_FEE);
//    }

    protected function calculateSellOrder(float|int $price): float
    {
        return $price * (1 - self::MARKET_FEE - self::MARKET_SETUP);
    }

    protected function calculateBuyOrder(float|int $price): float
    {
        return $price * (1 - self::MARKET_SETUP);
    }

    public function calculateProfitGrade(float $quotient): string
    {
        return match (true) {
            $quotient >= 1800 => 'S',
            $quotient >= 900 => 'A',
            $quotient >= 300 => 'B',
            $quotient >= 0 => 'C',
            default => 'D',
        };
    }

    public function calculateWeightProfitQuotient(float|int $profit, int $weight): float
    {
        if ($profit === 0) {
            return 0.0;
        }
        return $profit / $weight;
    }
}
