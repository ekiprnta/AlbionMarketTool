<?php

declare(strict_types=1);

namespace MZierdt\Albion\AlbionDataAPI;

class MaterialsApiService extends ApiService
{
    final public const MATERIAL_TIERS = 'T4_rune,T4_soul,T4_relic,T4_shard_avalonian,T5_rune,T5_soul,T5_relic,T5_shard_avalonian,T6_rune,T6_soul,T6_relic,T6_shard_avalonian,T7_rune,T7_soul,T7_relic,T7_shard_avalonian,T8_rune,T8_soul,T8_relic,T8_shard_avalonian';

    public function getMaterials()
    {
        $apiUrl = self::BASE_URL . self::MATERIAL_TIERS;
        return $this->get($apiUrl, ['locations' => self::CITY_ALL]);
    }
}