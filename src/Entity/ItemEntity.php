<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity;

use DateTimeImmutable;
use DateTimeZone;

class ItemEntity
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

    private const TIER_T2 = '2';
    private const TIER_T3 = '3';
    private const TIER_T4 = '4';
    private const TIER_T4_1 = '41';
    private const TIER_T4_2 = '42';
    private const TIER_T4_3 = '43';
    private const TIER_T5 = '5';
    private const TIER_T5_1 = '51';
    private const TIER_T5_2 = '52';
    private const TIER_T5_3 = '53';
    private const TIER_T6 = '6';
    private const TIER_T6_1 = '61';
    private const TIER_T6_2 = '62';
    private const TIER_T6_3 = '63';
    private const TIER_T7 = '7';
    private const TIER_T7_1 = '71';
    private const TIER_T7_2 = '72';
    private const TIER_T7_3 = '73';
    private const TIER_T8 = '8';
    private const TIER_T8_1 = '81';
    private const TIER_T8_2 = '82';
    private const TIER_T8_3 = '83';

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

    private const T20_FACTOR_FAME = 1;
    private const T30_FACTOR_FAME = 5;
    private const T40_FACTOR_FAME = 15;
    private const T41_FACTOR_FAME = 30;
    private const T42_FACTOR_FAME = 60;
    private const T43_FACTOR_FAME = 120;
    private const T50_FACTOR_FAME = 60;
    private const T51_FACTOR_FAME = 120;
    private const T52_FACTOR_FAME = 240;
    private const T53_FACTOR_FAME = 480;
    private const T60_FACTOR_FAME = 180;
    private const T61_FACTOR_FAME = 360;
    private const T62_FACTOR_FAME = 720;
    private const T63_FACTOR_FAME = 1440;
    private const T70_FACTOR_FAME = 430;
    private const T71_FACTOR_FAME = 860;
    private const T72_FACTOR_FAME = 1720;
    private const T73_FACTOR_FAME = 3440;
    private const T80_FACTOR_FAME = 930;
    private const T81_FACTOR_FAME = 1860;
    private const T82_FACTOR_FAME = 3720;
    private const T83_FACTOR_FAME = 7440;

    public const CLASS_WARRIOR = 'warrior';
    public const CLASS_MAGE = 'mage';
    public const CLASS_HUNTER = 'hunter';

    private string $tier;
    private string $name;
    private string $weaponGroup;
    private string $realName;
    private string $class;
    private string $city;
    private int $quality;
    private int $sellOrderPrice;
    private DateTimeImmutable $sellOrderPriceDate;
    private int $buyOrderPrice;
    private DateTimeImmutable $buyOrderPriceDate;
    private string $primaryResource;
    private int $primaryResourceAmount;
    private ?string $secondaryResource;
    private ?int $secondaryResourceAmount;
    private string $bonusCity;
    private float $fameFactor;
    private ?int $amountInStorage;
    private float $weight;
    private int $itemValue;


    public function __construct(array $itemData)
    {
        $sellOrderPriceDate = $this->getDateTimeImmutable($itemData['sellOrderPriceDate']);
        $buyOrderPriceDate = $this->getDateTimeImmutable($itemData['buyOrderPriceDate']);
        $weight = $this->setWeight($itemData);

        $this->tier = $itemData['tier'];
        $this->name = $itemData['name'];
        $this->weaponGroup = $itemData['weaponGroup'];
        $this->realName = $itemData['realName'] ?? 'No Name given WTF';
        $this->class = $itemData['class'] ?? 'No CLass ? wrong input?';
        $this->city = $itemData['city'];
        $this->quality = (int) $itemData['quality'];
        $this->sellOrderPrice = (int) $itemData['sellOrderPrice'];
        $this->sellOrderPriceDate = $sellOrderPriceDate;
        $this->buyOrderPrice = (int) $itemData['buyOrderPrice'];
        $this->buyOrderPriceDate = $buyOrderPriceDate;
        $this->primaryResource = $itemData['primaryResource'] ?? 'primR';
        $this->primaryResourceAmount = (int) $itemData['primaryResourceAmount'];
        $this->secondaryResource = $itemData['secondaryResource'];
        $this->secondaryResourceAmount = (int) $itemData['secondaryResourceAmount'];
        $this->bonusCity = $itemData['bonusCity'] ?? 'bonusCity';
        $this->fameFactor = $this->setFameFactor();
        $this->amountInStorage = $itemData['amountInStorage'];
        $this->weight = $weight;
        $this->itemValue = ($this->primaryResourceAmount + $this->secondaryResourceAmount) * $this->getNutritonFactor();
    }

    public function getItemValue(): int
    {
        return $this->itemValue;
    }

    private function setFameFactor(): float
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

    public function getClass(): mixed
    {
        return $this->class;
    }

    public function getAmountInStorage(): mixed
    {
        return $this->amountInStorage;
    }

    public function getTier(): string
    {
        return $this->tier;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getQuality(): int
    {
        return $this->quality;
    }

    public function getSellOrderPrice(): int
    {
        return $this->sellOrderPrice;
    }

    public function getSellOrderPriceDate(): DateTimeImmutable|bool
    {
        return $this->sellOrderPriceDate;
    }

    public function getSellOrderPriceDateString(): string
    {
        return $this->sellOrderPriceDate->format('Y-m-d H:i:s');
    }

    public function getBuyOrderPrice(): int
    {
        return $this->buyOrderPrice;
    }

    public function getBuyOrderPriceDate(): DateTimeImmutable|bool
    {
        return $this->buyOrderPriceDate;
    }

    public function getBuyOrderPriceDateString(): string
    {
        return $this->buyOrderPriceDate->format('Y-m-d H:i:s');
    }

    public function getPrimaryResource(): string
    {
        return $this->primaryResource;
    }

    public function getPrimaryResourceAmount(): int
    {
        return $this->primaryResourceAmount;
    }

    public function getSecondaryResource(): ?string
    {
        return $this->secondaryResource;
    }

    public function getSecondaryResourceAmount(): ?int
    {
        return $this->secondaryResourceAmount;
    }

    public function getBonusCity(): string
    {
        return $this->bonusCity;
    }

    public function getFameFactor(): float
    {
        return $this->fameFactor;
    }

    private function getDateTimeImmutable(mixed $date): DateTimeImmutable|bool
    {
        if ($date === null) {
            return DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2000-00-00 00:00:00');
        }
        $date = str_replace('T', ' ', $date);
        return DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $date, new DateTimeZone('Europe/London'));
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

    public function getRealName(): string
    {
        return $this->realName;
    }

    private function getNutritonFactor()
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
