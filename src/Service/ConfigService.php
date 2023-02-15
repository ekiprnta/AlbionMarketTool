<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

class ConfigService
{
    private const PATH_TO_ITEM_CONFIG = __DIR__ . '/../../config/data/ItemConfig.json';
    private const PATH_TO_RESOURCE_CONFIG = __DIR__ . '/../../config/data/ResourceConfig.json';
    private const PATH_TO_RAW_RESOURCE_CONFIG = __DIR__ . '/../../config/data/RawResourceConfig.json';
    private const PATH_TO_JOURNAL_CONFIG = __DIR__ . '/../../config/data/JournalConfig.json';
    private const PATH_TO_BLACK_MARKET_SELLS = __DIR__ . '/../../config/BlackmarketSellAmount.json';
    private const PATH_TO_TRANSMUTATION_COST = __DIR__ . '/../../config/transmutation/transmuteCost.json';
    private const PATH_TO_TRANSMUTATION_WAYS = __DIR__ . '/../../config/transmutation/transmuteWays.json';
    private const PATH_TO_CAPES_CONFIG = __DIR__ . '/../../config/data/CapesAndRoyalConfig.json';

    public function getItemConfig()
    {
        $json = file_get_contents(self::PATH_TO_ITEM_CONFIG);
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }

    public function getResourceConfig()
    {
        $json = file_get_contents(self::PATH_TO_RESOURCE_CONFIG);
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }

    public function getJournalConfig()
    {
        $json = file_get_contents(self::PATH_TO_JOURNAL_CONFIG);
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }

    public function getBlackMarketSells()
    {
        $json = file_get_contents(self::PATH_TO_BLACK_MARKET_SELLS);
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }

    public function getRawResourceConfig()
    {
        $json = file_get_contents(self::PATH_TO_RAW_RESOURCE_CONFIG);
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }

    public function getTransmutationWays()
    {
        $json = file_get_contents(self::PATH_TO_TRANSMUTATION_WAYS);
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }

    public function getTransmutationCost()
    {
        $json = file_get_contents(self::PATH_TO_TRANSMUTATION_COST);
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }

    public function getMaterialConfig()
    {
    }

    public function getCapesAndRoyalConfig()
    {
        $json = file_get_contents(self::PATH_TO_CAPES_CONFIG);
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }
}
