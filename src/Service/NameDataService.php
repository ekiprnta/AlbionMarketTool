<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

use RuntimeException;

class NameDataService
{
    private const PATH_TO_JSON = __DIR__ . '/NameData.json';

    public static function getNameDataArray()
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
        $nameData = self::getNameDataArray();
        return $nameData['resource'][$type]['bonusCity'];
    }

    public static function getStatsForItem(string $class, string $weaponGroupName, string $name)
    {
        $nameData = self::getNameDataArray();
        foreach ($nameData[$class][$weaponGroupName] as $itemName => $item) {
            $lowerCaseIdSnippte = strtolower($item['id_snippet']);
            if ($lowerCaseIdSnippte === $name) {
                return $nameData[$class][$weaponGroupName][$itemName];
            }
        }
        throw new RuntimeException(' IN Name Data Service in getStatsForItem nothing found');
    }
}
