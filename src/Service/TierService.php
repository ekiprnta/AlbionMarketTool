<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

class TierService
{
    public function splitIntoTierAndName(string $itemId): array
    {
        $itemId = strtolower($itemId);
        $itemIdArray = explode('_', $itemId);

        if ($itemIdArray[0] === 't2' || $itemIdArray[0] === 't3') {
            return [
                'tier' => $this->tierConverter(array_shift($itemIdArray)),
                'name' => implode('_', $itemIdArray),
            ];
        }

        $preTier = array_shift($itemIdArray);
        $itemName = implode('_', $itemIdArray);

        if (! str_contains($itemName, '@')) {
            return [
                'tier' => $this->tierConverter($preTier),
                'name' => $itemName,
            ];
        }

        $explodedNameEnchantment = explode('@', $itemName);

        return [
            'tier' => $this->tierConverter($preTier . $explodedNameEnchantment[1]),
            'name' => $explodedNameEnchantment[0],
        ];
    }

    private function tierConverter(string $tierString): int
    {
        return (int) ltrim($tierString, 't');
    }
}
