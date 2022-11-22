<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

use RuntimeException;

class ItemDataService
{
    private const PATH_TO_JSON = __DIR__ . '/../../config/data/ItemConfig.json';

    /**
     * @throws \JsonException
     */
    public static function getItemConfig()
    {
        $json = file_get_contents(self::PATH_TO_JSON);
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }

    public static function getFilteredArray(string $itemString): string
    {
        $itemAsArray = explode('_', $itemString);
        array_shift($itemAsArray);
        $itemWithoutTier = implode('_', $itemAsArray);
        if (str_contains($itemWithoutTier, '@')) {
            $itemWithoutTier = explode('@', $itemWithoutTier);
            return $itemWithoutTier[0];
        }
        return $itemWithoutTier;
    }

    public static function getBonusCityForResource(string $type)
    {
        $nameData = self::getItemConfig();
        return $nameData['resource'][$type]['bonusCity'];
    }

    public static function getStatsForItem(string $class, string $weaponGroupName, string $name)
    {
        $nameData = self::getItemConfig();
        foreach ($nameData[$class][$weaponGroupName] as $itemName => $item) {
            $lowerCaseIdSnippet = strtolower($item['id_snippet']);
            if ($lowerCaseIdSnippet === $name) {
                return $nameData[$class][$weaponGroupName][$itemName];
            }
        }
        throw new RuntimeException(' IN Name Data Service in getStatsForItem nothing found');
    }

    public static function getPrimResource(string $name)
    {
        $nameData = self::getItemConfig();
        foreach ($nameData as $weaponGroup) {
            foreach ($weaponGroup as $class) {
                if (array_key_exists($name, $class)) {
                    return $class[$name]['primaryResource'];
                }
            }
        }
        return null;
    }

    public static function getSecResource(string $name)
    {
        $nameData = self::getItemConfig();
        foreach ($nameData as $weaponGroup) {
            foreach ($weaponGroup as $class) {
                if (array_key_exists($name, $class)) {
                    return $class[$name]['secondaryResource'];
                }
            }
        }
        return null;
    }

    public static function getStatsJournals(string $tier)
    {
        $nameData = self::getItemConfig();
        return $nameData['journal']['stats'][$tier];
    }

    public static function getAllBonusItemForCity(string $city): array
    {
        $nameData = self::getItemConfig();
        $itemNames = [];
        foreach ($nameData as $className => $class) {
            foreach ($class as $weaponGroupName => $weaponGroup) {
                $array = current($weaponGroup);
                if (! (is_array($array) && array_key_exists('bonusCity', $array))) {
                    continue;
                }
                if ($array['bonusCity'] === $city) {
                    $itemNames[] = [$weaponGroupName, $className];
                }
            }
        }
        return $itemNames;
    }
}
