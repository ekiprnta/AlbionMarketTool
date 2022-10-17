<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

use MZierdt\Albion\HttpClient;

class ApiService // Buy Order ist buy_price_max
{
    private const BASE_URL = 'https://www.albion-online-data.com/api/v2/stats/prices/';
    private const RESOURCE_TIERS_WITH_PLACEHOLDER = 'T2_%s,T3_%s,T4_%s,T5_%s,T6_%s,T7_%s,T8_%s,T4_%s_level1@1,T5_%s_level1@1,T6_%s_level1@1,T7_%s_level1@1,T8_%s_level1@1,T4_%s_level2@2,T5_%s_level2@2,T6_%s_level2@2,T7_%s_level2@2,T8_%s_level2@2,T4_%s_level3@3,T5_%s_level3@3,T6_%s_level3@3,T7_%s_level3@3,T8_%s_level3@3';

    private const RESOURCE_PLANKS = 'Planks';
    private const RESOURCE_STONEBLOCK = 'T2_StoneBlock,T3_StoneBlock,T4_StoneBlock,T5_StoneBlock,T6_StoneBlock,T7_StoneBlock,T8_StoneBlock';
    private const RESOURCE_METALBAR = 'MetalBar';
    private const RESOURCE_LEATHER = 'Leather';
    private const RESOURCE_CLOTH = 'Cloth';

    private const CITY_LYMHURST = 'Lymhurst';
    private const CITY_FORTSTERLING = 'FortSterling';
    private const CITY_BRIDGEWATCH = 'Bridgewatch';
    private const CITY_MARTLOCK = 'Martlock';
    private const CITY_THETFORD = 'Thetford';
    private const CITY_Caerleon = 'Caerleon';

    public const ITEM_WARRIOR_HELMET = 'plateHelmet';
    public const ITEM_WARRIOR_ARMOR = 'plateAmor';
    public const ITEM_WARRIOR_BOOTS = 'plateBoots';
    public const ITEM_WARRIOR_SWORD = 'sword';
    public const ITEM_WARRIOR_AXE = 'axe';
    public const ITEM_WARRIOR_MACE = 'mace';
    public const ITEM_WARRIOR_HAMMER = 'hammer';
    public const ITEM_WARRIOR_WAR_GLOVE = 'warGlove';
    public const ITEM_WARRIOR_CROSSBOW = 'crossbow';
    public const ITEM_WARRIOR_SHIELD = 'shield';

    public const ITEM_MAGE_HELMET = 'clothHelmet';
    public const ITEM_MAGE_ARMOR = 'clothAmor';
    public const ITEM_MAGE_BOOTS = 'clothBoots';
    public const ITEM_MAGE_FIRE_STAFF = 'fire';
    public const ITEM_MAGE_HOLY_STAFF = 'holy';
    public const ITEM_MAGE_ARCANE_STAFF = 'arcane';
    public const ITEM_MAGE_FROST_STAFF = 'frost';
    public const ITEM_MAGE_CURSE_STAFF = 'curse';
    public const ITEM_MAGE_TOME_STAFF = 'tome';

    public const ITEM_HUNTER_HELMET = 'leatherHelmet';
    public const ITEM_HUNTER_ARMOR = 'leatherAmor';
    public const ITEM_HUNTER_BOOTS = 'leatherBoots';
    public const ITEM_HUNTER_BOW = 'bow';
    public const ITEM_HUNTER_SPEAR = 'spear';
    public const ITEM_HUNTER_NATURE_STAFF = 'nature';
    public const ITEM_HUNTER_DAGGER = 'dagger';
    public const ITEM_HUNTER_QUARTERSTAFF = 'quarterstaff';
    public const ITEM_HUNTER_TORCH = 'torch';

    private const QUALITY_NORMAL = 1;
    private const QUALITY_GOOD = 2;
    private const QUALITY_OUTSTANDING = 3;
    private const QUALITY_EXCELLENT = 4;
    private const QUALITY_MASTERPIECE = 5;

    public function __construct(private HttpClient $httpClient)
    {
    }

    public function getResource(string $resourceType)
    {
        $apiUrl = match ($resourceType) {
            'metalBar' => self::BASE_URL . $this->apiUrlAssembler(self::RESOURCE_METALBAR),
            'planks' => self::BASE_URL . $this->apiUrlAssembler(self::RESOURCE_PLANKS),
            'cloth' => self::BASE_URL . $this->apiUrlAssembler(self::RESOURCE_CLOTH),
            'leather' => self::BASE_URL . $this->apiUrlAssembler(self::RESOURCE_LEATHER),
        };

        $cities = sprintf(
            '%s,%s,%s,%s,%s',
            self::CITY_BRIDGEWATCH,
            self::CITY_FORTSTERLING,
            self::CITY_LYMHURST,
            self::CITY_MARTLOCK,
            self::CITY_THETFORD
        );
        return $this->jsonDecode($this->httpClient->get($apiUrl, ['locations' => $cities]));
    }

    public function getBlackMarketItem(string $itemName)
    {
        $apiURL = match ($itemName) {
            self::ITEM_WARRIOR_HELMET => self::BASE_URL . self::ITEM_WARRIOR_HELMET,
            self::ITEM_WARRIOR_ARMOR => self::BASE_URL . self::ITEM_WARRIOR_ARMOR,
            self::ITEM_WARRIOR_BOOTS => self::BASE_URL . self::ITEM_WARRIOR_BOOTS,
            self::ITEM_WARRIOR_SWORD => self::BASE_URL . self::ITEM_WARRIOR_SWORD,
            self::ITEM_WARRIOR_AXE => self::BASE_URL . self::ITEM_WARRIOR_AXE,
            self::ITEM_WARRIOR_MACE => self::BASE_URL . self::ITEM_WARRIOR_MACE,
            self::ITEM_WARRIOR_HAMMER => self::BASE_URL . self::ITEM_WARRIOR_HAMMER,
            self::ITEM_WARRIOR_WAR_GLOVE => self::BASE_URL . self::ITEM_WARRIOR_WAR_GLOVE,
            self::ITEM_WARRIOR_CROSSBOW => self::BASE_URL . self::ITEM_WARRIOR_CROSSBOW,
            self::ITEM_WARRIOR_SHIELD => self::BASE_URL . self::ITEM_WARRIOR_SHIELD,
            self::ITEM_MAGE_HELMET => self::BASE_URL . self::ITEM_MAGE_HELMET,
            self::ITEM_MAGE_ARMOR => self::BASE_URL . self::ITEM_MAGE_ARMOR,
            self::ITEM_MAGE_BOOTS => self::BASE_URL . self::ITEM_MAGE_BOOTS,
            self::ITEM_MAGE_FIRE_STAFF => self::BASE_URL . self::ITEM_MAGE_FIRE_STAFF,
            self::ITEM_MAGE_HOLY_STAFF => self::BASE_URL . self::ITEM_MAGE_HOLY_STAFF,
            self::ITEM_MAGE_ARCANE_STAFF => self::BASE_URL . self::ITEM_MAGE_ARCANE_STAFF,
            self::ITEM_MAGE_FROST_STAFF => self::BASE_URL . self::ITEM_MAGE_FROST_STAFF,
            self::ITEM_MAGE_CURSE_STAFF => self::BASE_URL . self::ITEM_MAGE_CURSE_STAFF,
            self::ITEM_MAGE_TOME_STAFF => self::BASE_URL . self::ITEM_MAGE_TOME_STAFF,
            self::ITEM_HUNTER_HELMET => self::BASE_URL . self::ITEM_HUNTER_HELMET,
            self::ITEM_HUNTER_ARMOR => self::BASE_URL . self::ITEM_HUNTER_ARMOR,
            self::ITEM_HUNTER_BOOTS => self::BASE_URL . self::ITEM_HUNTER_BOOTS,
            self::ITEM_HUNTER_BOW => self::BASE_URL . self::ITEM_HUNTER_BOW,
            self::ITEM_HUNTER_SPEAR => self::BASE_URL . self::ITEM_HUNTER_SPEAR,
            self::ITEM_HUNTER_NATURE_STAFF => self::BASE_URL . self::ITEM_HUNTER_NATURE_STAFF,
            self::ITEM_HUNTER_DAGGER => self::BASE_URL . self::ITEM_HUNTER_DAGGER,
            self::ITEM_HUNTER_QUARTERSTAFF => self::BASE_URL . self::ITEM_HUNTER_QUARTERSTAFF,
            self::ITEM_HUNTER_TORCH => self::BASE_URL . self::ITEM_HUNTER_TORCH,
        };

        return [];
    }

    private function apiUrlAssembler(string $replacement, string $stringWithPlaceholders = self::RESOURCE_TIERS_WITH_PLACEHOLDER)
    {
        return str_replace('%s', $replacement, $stringWithPlaceholders);
    }

    private function jsonDecode(string $json)
    {
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }
}
