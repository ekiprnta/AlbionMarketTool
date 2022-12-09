<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity;

class ItemEntity extends AlbionItemEntity
{
    public const ITEM_WARRIOR_HELMET = 'plateHelmet';
    public const ITEM_WARRIOR_ARMOR = 'plateArmor';
    public const ITEM_WARRIOR_BOOTS = 'plateBoots';
    public const ITEM_WARRIOR_SWORD = 'sword';
    public const ITEM_WARRIOR_AXE = 'axe';
    public const ITEM_WARRIOR_MACE = 'mace';
    public const ITEM_WARRIOR_HAMMER = 'hammer';
    public const ITEM_WARRIOR_WAR_GLOVE = 'warGlove';
    public const ITEM_WARRIOR_CROSSBOW = 'crossbow';
    public const ITEM_WARRIOR_SHIELD = 'shield';

    public const ITEM_MAGE_HELMET = 'clothCowl';
    public const ITEM_MAGE_ARMOR = 'clothRobe';
    public const ITEM_MAGE_BOOTS = 'clothSandals';
    public const ITEM_MAGE_FIRE_STAFF = 'fireStaff';
    public const ITEM_MAGE_HOLY_STAFF = 'holyStaff';
    public const ITEM_MAGE_ARCANE_STAFF = 'arcaneStaff';
    public const ITEM_MAGE_FROST_STAFF = 'frostStaff';
    public const ITEM_MAGE_CURSE_STAFF = 'curseStaff';
    public const ITEM_MAGE_TOME_STAFF = 'tome';

    public const ITEM_HUNTER_HELMET = 'leatherHood';
    public const ITEM_HUNTER_ARMOR = 'leatherJacket';
    public const ITEM_HUNTER_BOOTS = 'leatherShoes';
    public const ITEM_HUNTER_BOW = 'bow';
    public const ITEM_HUNTER_SPEAR = 'spear';
    public const ITEM_HUNTER_NATURE_STAFF = 'nature';
    public const ITEM_HUNTER_DAGGER = 'dagger';
    public const ITEM_HUNTER_QUARTERSTAFF = 'quarterstaff';
    public const ITEM_HUNTER_TORCH = 'torch';

    private const T20_WEIGHT_FACTOR = 0.1;
    private const T30_WEIGHT_FACTOR = 0.14;
    private const T40_WEIGHT_FACTOR = 0.21;
    private const T41_WEIGHT_FACTOR = 0.21;
    private const T42_WEIGHT_FACTOR = 0.21;
    private const T43_WEIGHT_FACTOR = 0.21;
    private const T50_WEIGHT_FACTOR = 0.316;
    private const T51_WEIGHT_FACTOR = 0.316;
    private const T52_WEIGHT_FACTOR = 0.316;
    private const T53_WEIGHT_FACTOR = 0.316;
    private const T60_WEIGHT_FACTOR = 0.475;
    private const T61_WEIGHT_FACTOR = 0.475;
    private const T62_WEIGHT_FACTOR = 0.475;
    private const T63_WEIGHT_FACTOR = 0.475;
    private const T70_WEIGHT_FACTOR = 0.7125;
    private const T71_WEIGHT_FACTOR = 0.7125;
    private const T72_WEIGHT_FACTOR = 0.7125;
    private const T73_WEIGHT_FACTOR = 0.7125;
    private const T80_WEIGHT_FACTOR = 1.056;
    private const T81_WEIGHT_FACTOR = 1.056;
    private const T82_WEIGHT_FACTOR = 1.056;
    private const T83_WEIGHT_FACTOR = 1.056;

    public const T20_NUTRITION_FACTOR = 4;
    public const T30_NUTRITION_FACTOR = 8;
    public const T40_NUTRITION_FACTOR = 16;
    public const T41_NUTRITION_FACTOR = 32;
    public const T42_NUTRITION_FACTOR = 64;
    public const T43_NUTRITION_FACTOR = 128;
    public const T50_NUTRITION_FACTOR = 32;
    public const T51_NUTRITION_FACTOR = 64;
    public const T52_NUTRITION_FACTOR = 128;
    public const T53_NUTRITION_FACTOR = 256;
    public const T60_NUTRITION_FACTOR = 64;
    public const T61_NUTRITION_FACTOR = 128;
    public const T62_NUTRITION_FACTOR = 256;
    public const T63_NUTRITION_FACTOR = 512;
    public const T70_NUTRITION_FACTOR = 128;
    public const T71_NUTRITION_FACTOR = 256;
    public const T72_NUTRITION_FACTOR = 512;
    public const T73_NUTRITION_FACTOR = 1024;
    public const T80_NUTRITION_FACTOR = 256;
    public const T81_NUTRITION_FACTOR = 512;
    public const T82_NUTRITION_FACTOR = 1024;
    public const T83_NUTRITION_FACTOR = 2048;

    private const T20_FACTOR_FAME = 1.5;
    private const T30_FACTOR_FAME = 7.5;
    private const T40_FACTOR_FAME = 22.5;
    private const T41_FACTOR_FAME = 45;
    private const T42_FACTOR_FAME = 90;
    private const T43_FACTOR_FAME = 180;
    private const T50_FACTOR_FAME = 90;
    private const T51_FACTOR_FAME = 180;
    private const T52_FACTOR_FAME = 360;
    private const T53_FACTOR_FAME = 720;
    private const T60_FACTOR_FAME = 270;
    private const T61_FACTOR_FAME = 540;
    private const T62_FACTOR_FAME = 1080;
    private const T63_FACTOR_FAME = 2160;
    private const T70_FACTOR_FAME = 645;
    private const T71_FACTOR_FAME = 1290;
    private const T72_FACTOR_FAME = 2580;
    private const T73_FACTOR_FAME = 5160;
    private const T80_FACTOR_FAME = 1395;
    private const T81_FACTOR_FAME = 2790;
    private const T82_FACTOR_FAME = 5580;
    private const T83_FACTOR_FAME = 11160;

    public const CLASS_WARRIOR = 'warrior';
    public const CLASS_MAGE = 'mage';
    public const CLASS_HUNTER = 'hunter';

    private string $weaponGroup;
    private int $quality;
    private string $primaryResource;
    private int $primaryResourceAmount;
    private ?string $secondaryResource;
    private ?int $secondaryResourceAmount;
    private string $bonusCity;
    private ?int $amountInStorage;
    private int $itemValue;
    private float $fame;


    public function __construct(array $itemResourceData)
    {
        parent::__construct($itemResourceData);

        $this->weight = $this->setWeight($itemResourceData);
        $this->weaponGroup = $itemResourceData['weaponGroup'];
        $this->quality = (int) $itemResourceData['quality'];

        $this->primaryResource = $itemResourceData['primaryResource'] ?? 'primR';
        $this->primaryResourceAmount = (int) $itemResourceData['primaryResourceAmount'];
        $this->secondaryResource = $itemResourceData['secondaryResource'];
        $this->secondaryResourceAmount = (int) $itemResourceData['secondaryResourceAmount'];

        $this->bonusCity = $itemResourceData['bonusCity'] ?? 'bonusCity';
        $this->amountInStorage = $itemResourceData['amountInStorage'];
        $this->itemValue = ($this->primaryResourceAmount + $this->secondaryResourceAmount) * $this->getNutritionFactor();
        $this->fame = $this->calculateFameFactor() * ($this->primaryResourceAmount + $this->secondaryResourceAmount);
    }

    public function getFame(): float
    {
        return $this->fame;
    }

    public function getItemValue(): int
    {
        return $this->itemValue;
    }

    private function calculateFameFactor(): float
    {
        return match ($this->tier) {
            self::TIER_T2 => self::T20_FACTOR_FAME,
            self::TIER_T3 => self::T30_FACTOR_FAME,
            self::TIER_T4 => self::T40_FACTOR_FAME,
            self::TIER_T4_1 => self::T41_FACTOR_FAME,
            self::TIER_T4_2 => self::T42_FACTOR_FAME,
            self::TIER_T4_3 => self::T43_FACTOR_FAME,
            self::TIER_T5 => self::T50_FACTOR_FAME,
            self::TIER_T5_1 => self::T51_FACTOR_FAME,
            self::TIER_T5_2 => self::T52_FACTOR_FAME,
            self::TIER_T5_3 => self::T53_FACTOR_FAME,
            self::TIER_T6 => self::T60_FACTOR_FAME,
            self::TIER_T6_1 => self::T61_FACTOR_FAME,
            self::TIER_T6_2 => self::T62_FACTOR_FAME,
            self::TIER_T6_3 => self::T63_FACTOR_FAME,
            self::TIER_T7 => self::T70_FACTOR_FAME,
            self::TIER_T7_1 => self::T71_FACTOR_FAME,
            self::TIER_T7_2 => self::T72_FACTOR_FAME,
            self::TIER_T7_3 => self::T73_FACTOR_FAME,
            self::TIER_T8 => self::T80_FACTOR_FAME,
            self::TIER_T8_1 => self::T81_FACTOR_FAME,
            self::TIER_T8_2 => self::T82_FACTOR_FAME,
            self::TIER_T8_3 => self::T83_FACTOR_FAME,
            default => throw new \InvalidArgumentException('wrong Factor in Item Entity')
        };
    }

    public function setAmountInStorage(mixed $amountInStorage): void
    {
        $this->amountInStorage = $amountInStorage;
    }


    public function getWeaponGroup(): mixed
    {
        return $this->weaponGroup;
    }

    public function getAmountInStorage(): mixed
    {
        return $this->amountInStorage;
    }

    public function getQuality(): int
    {
        return $this->quality;
    }


    public function getPrimaryResource(): string
    {
        return $this->primaryResource;
    }

    public function getPrimaryResourceAmount(): int
    {
        return $this->primaryResourceAmount;
    }

    public function getSecondaryResource(): string
    {
        return $this->secondaryResource ?? ' - ';
    }

    public function getSecondaryResourceAmount(): int
    {
        return $this->secondaryResourceAmount ?? 0;
    }

    public function getBonusCity(): string
    {
        return $this->bonusCity;
    }

    private function setWeight(array $itemData): float
    {
        $weightFactor = match ($itemData['tier']) {
            self::TIER_T2 => self::T20_WEIGHT_FACTOR,
            self::TIER_T3 => self::T30_WEIGHT_FACTOR,
            self::TIER_T4 => self::T40_WEIGHT_FACTOR,
            self::TIER_T4_1 => self::T41_WEIGHT_FACTOR,
            self::TIER_T4_2 => self::T42_WEIGHT_FACTOR,
            self::TIER_T4_3 => self::T43_WEIGHT_FACTOR,
            self::TIER_T5 => self::T50_WEIGHT_FACTOR,
            self::TIER_T5_1 => self::T51_WEIGHT_FACTOR,
            self::TIER_T5_2 => self::T52_WEIGHT_FACTOR,
            self::TIER_T5_3 => self::T53_WEIGHT_FACTOR,
            self::TIER_T6 => self::T60_WEIGHT_FACTOR,
            self::TIER_T6_1 => self::T61_WEIGHT_FACTOR,
            self::TIER_T6_2 => self::T62_WEIGHT_FACTOR,
            self::TIER_T6_3 => self::T63_WEIGHT_FACTOR,
            self::TIER_T7 => self::T70_WEIGHT_FACTOR,
            self::TIER_T7_1 => self::T71_WEIGHT_FACTOR,
            self::TIER_T7_2 => self::T72_WEIGHT_FACTOR,
            self::TIER_T7_3 => self::T73_WEIGHT_FACTOR,
            self::TIER_T8 => self::T80_WEIGHT_FACTOR,
            self::TIER_T8_1 => self::T81_WEIGHT_FACTOR,
            self::TIER_T8_2 => self::T82_WEIGHT_FACTOR,
            self::TIER_T8_3 => self::T83_WEIGHT_FACTOR,
            default => throw new \InvalidArgumentException('wrong tier in Item Entity')
        };

        return ($itemData['primaryResourceAmount'] + $itemData['secondaryResourceAmount']) * $weightFactor;
    }

    private function getNutritionFactor(): int
    {
        return match ($this->tier) {
            self::TIER_T2 => self::T20_NUTRITION_FACTOR,
            self::TIER_T3 => self::T30_NUTRITION_FACTOR,
            self::TIER_T4 => self::T40_NUTRITION_FACTOR,
            self::TIER_T4_1 => self::T41_NUTRITION_FACTOR,
            self::TIER_T4_2 => self::T42_NUTRITION_FACTOR,
            self::TIER_T4_3 => self::T43_NUTRITION_FACTOR,
            self::TIER_T5 => self::T50_NUTRITION_FACTOR,
            self::TIER_T5_1 => self::T51_NUTRITION_FACTOR,
            self::TIER_T5_2 => self::T52_NUTRITION_FACTOR,
            self::TIER_T5_3 => self::T53_NUTRITION_FACTOR,
            self::TIER_T6 => self::T60_NUTRITION_FACTOR,
            self::TIER_T6_1 => self::T61_NUTRITION_FACTOR,
            self::TIER_T6_2 => self::T62_NUTRITION_FACTOR,
            self::TIER_T6_3 => self::T63_NUTRITION_FACTOR,
            self::TIER_T7 => self::T70_NUTRITION_FACTOR,
            self::TIER_T7_1 => self::T71_NUTRITION_FACTOR,
            self::TIER_T7_2 => self::T72_NUTRITION_FACTOR,
            self::TIER_T7_3 => self::T73_NUTRITION_FACTOR,
            self::TIER_T8 => self::T80_NUTRITION_FACTOR,
            self::TIER_T8_1 => self::T81_NUTRITION_FACTOR,
            self::TIER_T8_2 => self::T82_NUTRITION_FACTOR,
            self::TIER_T8_3 => self::T83_NUTRITION_FACTOR,
            default => throw new \InvalidArgumentException('wrong Nutrition Factor in Item Entity')
        };
    }
}
