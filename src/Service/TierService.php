<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

class TierService
{
    private const TIER_T2 = '2';
    private const TIER_T3 = '3';
    private const TIER_T4 = '4';
    private const TIER_T4_1 = '4.1';
    private const TIER_T4_2 = '4.2';
    private const TIER_T4_3 = '4.3';
    private const TIER_T5 = '5';
    private const TIER_T5_1 = '5.1';
    private const TIER_T5_2 = '5.2';
    private const TIER_T5_3 = '5.3';
    private const TIER_T6 = '6';
    private const TIER_T6_1 = '6.1';
    private const TIER_T6_2 = '6.2';
    private const TIER_T6_3 = '6.3';
    private const TIER_T7 = '7';
    private const TIER_T7_1 = '7.1';
    private const TIER_T7_2 = '7.2';
    private const TIER_T7_3 = '7.3';
    private const TIER_T8 = '8';
    private const TIER_T8_1 = '8.1';
    private const TIER_T8_2 = '8.2';
    private const TIER_T8_3 = '8.3';


    public static function splitIntoTierAndName(string $itemId): array
    {
        $itemId = strtolower($itemId);
        $itemIdArray = explode('_', $itemId);

        if ($itemIdArray[0] === 't2' || $itemIdArray[0] === 't3') {
            return [
                'tier' => self::tierConverter(array_shift($itemIdArray)),
                'name' => implode('_', $itemIdArray),
            ];
        }
        $preTier = array_shift($itemIdArray);
        $itemName = implode('_', $itemIdArray);

        if (! str_contains($itemName, '@')) {
            return [
                'tier' => self::tierConverter($preTier),
                'name' => $itemName,
            ];
        }

        $explodedNameEnchantment = explode('@', $itemName);

        return [
            'tier' => self::tierConverter($preTier . $explodedNameEnchantment[1]),
            'name' => $explodedNameEnchantment[0],
        ];
    }

    private static function tierConverter(string $tierString): string
    {
        return match ($tierString) {
            't2' => self::TIER_T2,
            't3' => self::TIER_T3,
            't4' => self::TIER_T4,
            't41' => self::TIER_T4_1,
            't42' => self::TIER_T4_2,
            't43' => self::TIER_T4_3,
            't5' => self::TIER_T5,
            't51' => self::TIER_T5_1,
            't52' => self::TIER_T5_2,
            't53' => self::TIER_T5_3,
            't6' => self::TIER_T6,
            't61' => self::TIER_T6_1,
            't62' => self::TIER_T6_2,
            't63' => self::TIER_T6_3,
            't7' => self::TIER_T7,
            't71' => self::TIER_T7_1,
            't72' => self::TIER_T7_2,
            't73' => self::TIER_T7_3,
            't8' => self::TIER_T8,
            't81' => self::TIER_T8_1,
            't82' => self::TIER_T8_2,
            't83' => self::TIER_T8_3,
            default => throw new \InvalidArgumentException('wrong tier in Item Entity')
        };
    }
}
