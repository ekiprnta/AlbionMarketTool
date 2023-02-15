<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

class TierService
{
    public function splitIntoTierAndName(string $itemId): array
    {
        $itemId = strtolower($itemId);
        $itemIdArray = explode('_', $itemId);

        $tokenTier = match (end($itemIdArray)) {
            't4' => 40,
            't5' => 50,
            't6' => 60,
            't7' => 70,
            't8' => 80,
            default => null
        };
        if ($tokenTier !== null) {
            return [
                'tier' => $tokenTier,
                'name' => implode('_', $itemIdArray)
            ];
        }

        if ($itemIdArray[0] === 't2' || $itemIdArray[0] === 't3') {
            return [
                'tier' => $this->tierConverter(array_shift($itemIdArray)) . '0',
                'name' => implode('_', $itemIdArray),
            ];
        }

        $preTier = array_shift($itemIdArray);
        $itemName = implode('_', $itemIdArray);

        if (!str_contains($itemName, '@')) {
            return [
                'tier' => $this->tierConverter($preTier) . '0',
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

    public function journalSplitter(string $journalName): array
    {
        $journalName = strtolower($journalName);
        [$prefix, $class, $fillStatus] = explode('_', $journalName);
        return [
            'class' => $class,
            'fillStatus' => $fillStatus,
        ];
    }
}
