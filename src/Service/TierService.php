<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

class TierService
{
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

    private static function tierConverter(string $tierString): int
    {
        return (int) ltrim($tierString, 't');
    }
}
