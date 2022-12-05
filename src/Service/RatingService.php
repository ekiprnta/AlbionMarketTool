<?php

namespace MZierdt\Albion\Service;

use function PHPUnit\Framework\matches;

class RatingService
{
    public static function calculateCraftingGrade(float $quotient)
    {
        return match (true) {
            $quotient >= 1000 => 'S',
            $quotient >= 400 => 'A',
            $quotient >= 100 => 'B',
            $quotient >= 0 => 'C',
            default => 'D',
        };
    }
}