<?php

declare(strict_types=1);

namespace MZierdt\Albion\AlbionDataAPI;

class ItemApiService extends ApiService
{
    private const ITEM_TIERS_WITH_PLACEHOLDER = 'T2_%s,T3_%s,T4_%s,T5_%s,T6_%s,T7_%s,T8_%s,T4_%s@1,T5_%s@1,T6_%s@1,T7_%s@1,T8_%s@1,T4_%s@2,T5_%s@2,T6_%s@2,T7_%s@2,T8_%s@2,T4_%s@3,T5_%s@3,T6_%s@3,T7_%s@3,T8_%s@3,T4_%s@4,T5_%s@4,T6_%s@4,T7_%s@4,T8_%s@4';

    public function getItems(string $itemName): array
    {
        $apiUrl = $this->apiUrlAssembler($itemName, self::ITEM_TIERS_WITH_PLACEHOLDER);
        $params = [
            'locations' => self::CITY_ALL,
            'qualities' => self::QUALITY_GOOD,
        ];
        return $this->get($apiUrl, $params);
    }

    public function getCapes(string $itemName)
    {
        $apiUrl = $this->apiUrlAssembler($itemName, self::ITEM_TIERS_WITH_PLACEHOLDER);
        $params = [
            'locations' => self::CITY_ALL,
            'qualities' => self::QUALITY_GOOD,
        ];
        return $this->get($apiUrl, $params);
    }
}
