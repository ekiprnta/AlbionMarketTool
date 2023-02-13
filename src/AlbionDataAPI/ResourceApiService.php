<?php

declare(strict_types=1);

namespace MZierdt\Albion\AlbionDataAPI;

class ResourceApiService extends ApiService
{
    private const RESOURCE_TIERS_WITH_PLACEHOLDER = 'T2_%s,T3_%s,T4_%s,T5_%s,T6_%s,T7_%s,T8_%s,T4_%s_level1@1,T5_%s_level1@1,T6_%s_level1@1,T7_%s_level1@1,T8_%s_level1@1,T4_%s_level2@2,T5_%s_level2@2,T6_%s_level2@2,T7_%s_level2@2,T8_%s_level2@2,T4_%s_level3@3,T5_%s_level3@3,T6_%s_level3@3,T7_%s_level3@3,T8_%s_level3@3,T4_%s_level4@4,T5_%s_level4@4,T6_%s_level4@4,T7_%s_level4@4,T8_%s_level4@4';

    public function getResources(string $resourceType)
    {
        $apiUrl = $this->apiUrlAssembler($resourceType, self::RESOURCE_TIERS_WITH_PLACEHOLDER);

        return $this->get($apiUrl, ['locations' => self::CITY_ALL]);
    }
}