<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

class ConfigService
{
    private const PATH_TO_ITEM_CONFIG = __DIR__ . '/../../config/data/ItemConfig.json';
    private const PATH_TO_RESOURCE_CONFIG = __DIR__ . '/../../config/data/ResourceConfig.json';
    private const PATH_TO_JOURNAL_CONFIG = __DIR__ . '/../../config/data/JournalConfig.json';

    public static function getItemConfig()
    {
        $json = file_get_contents(self::PATH_TO_ITEM_CONFIG);
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }

    public static function getResourceConfig()
    {
        $json = file_get_contents(self::PATH_TO_RESOURCE_CONFIG);
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }

    public static function getJournalConfig()
    {
        $json = file_get_contents(self::PATH_TO_JOURNAL_CONFIG);
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }
}
