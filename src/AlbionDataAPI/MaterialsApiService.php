<?php

declare(strict_types=1);

namespace MZierdt\Albion\AlbionDataAPI;

class MaterialsApiService extends ApiService
{
    final public const MATERIAL_TIERS = 'T4_rune,T4_soul,T4_relic,T4_shard_avalonian,T5_rune,T5_soul,T5_relic,T5_shard_avalonian,T6_rune,T6_soul,T6_relic,T6_shard_avalonian,T7_rune,T7_soul,T7_relic,T7_shard_avalonian,T8_rune,T8_soul,T8_relic,T8_shard_avalonian';
    private const HEARTS = 'T1_FACTION_FOREST_TOKEN_1,T1_FACTION_HIGHLAND_TOKEN_1,T1_FACTION_STEPPE_TOKEN_1,T1_FACTION_MOUNTAIN_TOKEN_1,T1_FACTION_SWAMP_TOKEN_1,T1_FACTION_CAERLEON_TOKEN_1';
    private const CAPE_ARTIFACTS = 'T4_CAPEITEM_FW_BRIDGEWATCH_BP,T5_CAPEITEM_FW_BRIDGEWATCH_BP,T6_CAPEITEM_FW_BRIDGEWATCH_BP,T7_CAPEITEM_FW_BRIDGEWATCH_BP,T8_CAPEITEM_FW_BRIDGEWATCH_BP,T4_CAPEITEM_FW_FORTSTERLING_BP,T5_CAPEITEM_FW_FORTSTERLING_BP,T6_CAPEITEM_FW_FORTSTERLING_BP,T7_CAPEITEM_FW_FORTSTERLING_BP,T8_CAPEITEM_FW_FORTSTERLING_BP,T4_CAPEITEM_FW_LYMHURST_BP,T5_CAPEITEM_FW_LYMHURST_BP,T6_CAPEITEM_FW_LYMHURST_BP,T7_CAPEITEM_FW_LYMHURST_BP,T8_CAPEITEM_FW_LYMHURST_BP,T4_CAPEITEM_FW_MARTLOCK_BP,T5_CAPEITEM_FW_MARTLOCK_BP,T6_CAPEITEM_FW_MARTLOCK_BP,T7_CAPEITEM_FW_MARTLOCK_BP,T8_CAPEITEM_FW_MARTLOCK_BP,T4_CAPEITEM_FW_THETFORD_BP,T5_CAPEITEM_FW_THETFORD_BP,T6_CAPEITEM_FW_THETFORD_BP,T7_CAPEITEM_FW_THETFORD_BP,T8_CAPEITEM_FW_THETFORD_BP,T4_CAPEITEM_FW_CAERLEON_BP,T5_CAPEITEM_FW_CAERLEON_BP,T6_CAPEITEM_FW_CAERLEON_BP,T7_CAPEITEM_FW_CAERLEON_BP,T8_CAPEITEM_FW_CAERLEON_BP,T4_CAPEITEM_HERETIC_BP,T5_CAPEITEM_HERETIC_BP,T6_CAPEITEM_HERETIC_BP,T7_CAPEITEM_HERETIC_BP,T8_CAPEITEM_HERETIC_BP,T4_CAPEITEM_UNDEAD_BP,T5_CAPEITEM_UNDEAD_BP,T6_CAPEITEM_UNDEAD_BP,T7_CAPEITEM_UNDEAD_BP,T8_CAPEITEM_UNDEAD_BP,T4_CAPEITEM_KEEPER_BP,T5_CAPEITEM_KEEPER_BP,T6_CAPEITEM_KEEPER_BP,T7_CAPEITEM_KEEPER_BP,T8_CAPEITEM_KEEPER_BP,T4_CAPEITEM_MORGANA_BP,T5_CAPEITEM_MORGANA_BP,T6_CAPEITEM_MORGANA_BP,T7_CAPEITEM_MORGANA_BP,T8_CAPEITEM_MORGANA_BP,T4_CAPEITEM_DEMON_BP,T5_CAPEITEM_DEMON_BP,T6_CAPEITEM_DEMON_BP,T7_CAPEITEM_DEMON_BP,T8_CAPEITEM_DEMON_BP';
    private const ROYAL_SIGILS = 'QUESTITEM_TOKEN_ROYAL_T4,QUESTITEM_TOKEN_ROYAL_T5,QUESTITEM_TOKEN_ROYAL_T6,QUESTITEM_TOKEN_ROYAL_T7,QUESTITEM_TOKEN_ROYAL_T8,';

    public function getMaterials()
    {
        $apiUrl = self::BASE_URL . self::MATERIAL_TIERS;
        return $this->get($apiUrl, ['locations' => self::CITY_ALL]);
    }

    public function getHearts()
    {
        $apiUrl = self::BASE_URL . self::HEARTS;
        return $this->get($apiUrl, ['locations' => self::CITY_ALL]);
    }

    public function getCapeArtifacts()
    {
        $apiUrl = self::BASE_URL . self::CAPE_ARTIFACTS;
        return $this->get($apiUrl, ['locations' => self::CITY_ALL]);
    }

    public function getRoyalSigils()
    {
        $apiUrl = self::BASE_URL . self::ROYAL_SIGILS;
        return $this->get($apiUrl, ['locations' => self::CITY_ALL]);
    }
}
