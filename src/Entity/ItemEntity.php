<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity;

use Doctrine\ORM\Mapping\ChangeTrackingPolicy;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Table(name: 'items')]
class ItemEntity extends AlbionItemEntity
{
    private const T20_WEIGHT_FACTOR = 0.1;
    private const T30_WEIGHT_FACTOR = 0.14;
    private const T40_WEIGHT_FACTOR = 0.21;
    private const T41_WEIGHT_FACTOR = 0.21;
    private const T42_WEIGHT_FACTOR = 0.21;
    private const T43_WEIGHT_FACTOR = 0.21;
    private const T44_WEIGHT_FACTOR = 0.21;
    private const T50_WEIGHT_FACTOR = 0.316;
    private const T51_WEIGHT_FACTOR = 0.316;
    private const T52_WEIGHT_FACTOR = 0.316;
    private const T53_WEIGHT_FACTOR = 0.316;
    private const T54_WEIGHT_FACTOR = 0.316;
    private const T60_WEIGHT_FACTOR = 0.475;
    private const T61_WEIGHT_FACTOR = 0.475;
    private const T62_WEIGHT_FACTOR = 0.475;
    private const T63_WEIGHT_FACTOR = 0.475;
    private const T64_WEIGHT_FACTOR = 0.475;
    private const T70_WEIGHT_FACTOR = 0.7125;
    private const T71_WEIGHT_FACTOR = 0.7125;
    private const T72_WEIGHT_FACTOR = 0.7125;
    private const T73_WEIGHT_FACTOR = 0.7125;
    private const T74_WEIGHT_FACTOR = 0.7125;
    private const T80_WEIGHT_FACTOR = 1.056;
    private const T81_WEIGHT_FACTOR = 1.056;
    private const T82_WEIGHT_FACTOR = 1.056;
    private const T83_WEIGHT_FACTOR = 1.056;
    private const T84_WEIGHT_FACTOR = 1.056;

    final public const T20_NUTRITION_FACTOR = 4;
    final public const T30_NUTRITION_FACTOR = 8;
    final public const T40_NUTRITION_FACTOR = 16;
    final public const T41_NUTRITION_FACTOR = 32;
    final public const T42_NUTRITION_FACTOR = 64;
    final public const T43_NUTRITION_FACTOR = 128;
    final public const T44_NUTRITION_FACTOR = 256;
    final public const T50_NUTRITION_FACTOR = 32;
    final public const T51_NUTRITION_FACTOR = 64;
    final public const T52_NUTRITION_FACTOR = 128;
    final public const T53_NUTRITION_FACTOR = 256;
    final public const T54_NUTRITION_FACTOR = 512;
    final public const T60_NUTRITION_FACTOR = 64;
    final public const T61_NUTRITION_FACTOR = 128;
    final public const T62_NUTRITION_FACTOR = 256;
    final public const T63_NUTRITION_FACTOR = 512;
    final public const T64_NUTRITION_FACTOR = 1024;
    final public const T70_NUTRITION_FACTOR = 128;
    final public const T71_NUTRITION_FACTOR = 256;
    final public const T72_NUTRITION_FACTOR = 512;
    final public const T73_NUTRITION_FACTOR = 1024;
    final public const T74_NUTRITION_FACTOR = 2048;
    final public const T80_NUTRITION_FACTOR = 256;
    final public const T81_NUTRITION_FACTOR = 512;
    final public const T82_NUTRITION_FACTOR = 1024;
    final public const T83_NUTRITION_FACTOR = 2048;
    final public const T84_NUTRITION_FACTOR = 4096;

    private const T20_FACTOR_FAME = 1.5;
    private const T30_FACTOR_FAME = 7.5;
    private const T40_FACTOR_FAME = 22.5;
    private const T41_FACTOR_FAME = 45;
    private const T42_FACTOR_FAME = 90;
    private const T43_FACTOR_FAME = 180;
    private const T44_FACTOR_FAME = 360;
    private const T50_FACTOR_FAME = 90;
    private const T51_FACTOR_FAME = 180;
    private const T52_FACTOR_FAME = 360;
    private const T53_FACTOR_FAME = 720;
    private const T54_FACTOR_FAME = 1440;
    private const T60_FACTOR_FAME = 270;
    private const T61_FACTOR_FAME = 540;
    private const T62_FACTOR_FAME = 1080;
    private const T63_FACTOR_FAME = 2160;
    private const T64_FACTOR_FAME = 4320;
    private const T70_FACTOR_FAME = 645;
    private const T71_FACTOR_FAME = 1290;
    private const T72_FACTOR_FAME = 2580;
    private const T73_FACTOR_FAME = 5160;
    private const T74_FACTOR_FAME = 10320;
    private const T80_FACTOR_FAME = 1395;
    private const T81_FACTOR_FAME = 2790;
    private const T82_FACTOR_FAME = 5580;
    private const T83_FACTOR_FAME = 11160;
    private const T84_FACTOR_FAME = 22320;

    final public const CLASS_WARRIOR = 'warrior';
    final public const CLASS_MAGE = 'mage';
    final public const CLASS_HUNTER = 'hunter';

    #[Id, Column(type: 'string')]
    private string $weaponGroup;
    #[Column(type: 'integer')]
    private int $quality;
    #[Column(type: 'string')]
    private string $primaryResource;
    #[Column(type: 'integer')]
    private int $primaryResourceAmount;
    #[Column(type: 'string', nullable: true)]
    private ?string $secondaryResource;
    #[Column(type: 'integer', nullable: true)]
    private ?int $secondaryResourceAmount;
    #[Column(type: 'string')]
    private string $bonusCity;
    #[Column(type: 'integer')]
    private int $itemValue;
    #[Column(type: 'float')]
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
        $this->itemValue = ($this->primaryResourceAmount + $this->secondaryResourceAmount) * $this->getNutritionFactor(
            );
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
            self::TIER_T4_4 => self::T44_FACTOR_FAME,
            self::TIER_T5 => self::T50_FACTOR_FAME,
            self::TIER_T5_1 => self::T51_FACTOR_FAME,
            self::TIER_T5_2 => self::T52_FACTOR_FAME,
            self::TIER_T5_3 => self::T53_FACTOR_FAME,
            self::TIER_T5_4 => self::T54_FACTOR_FAME,
            self::TIER_T6 => self::T60_FACTOR_FAME,
            self::TIER_T6_1 => self::T61_FACTOR_FAME,
            self::TIER_T6_2 => self::T62_FACTOR_FAME,
            self::TIER_T6_3 => self::T63_FACTOR_FAME,
            self::TIER_T6_4 => self::T64_FACTOR_FAME,
            self::TIER_T7 => self::T70_FACTOR_FAME,
            self::TIER_T7_1 => self::T71_FACTOR_FAME,
            self::TIER_T7_2 => self::T72_FACTOR_FAME,
            self::TIER_T7_3 => self::T73_FACTOR_FAME,
            self::TIER_T7_4 => self::T74_FACTOR_FAME,
            self::TIER_T8 => self::T80_FACTOR_FAME,
            self::TIER_T8_1 => self::T81_FACTOR_FAME,
            self::TIER_T8_2 => self::T82_FACTOR_FAME,
            self::TIER_T8_3 => self::T83_FACTOR_FAME,
            self::TIER_T8_4 => self::T84_FACTOR_FAME,
            default => throw new \InvalidArgumentException('wrong Factor in Item Entity')
        };
    }

    public function getWeaponGroup(): mixed
    {
        return $this->weaponGroup;
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
        $weightFactor = match ((int) $itemData['tier']) {
            self::TIER_T2 => self::T20_WEIGHT_FACTOR,
            self::TIER_T3 => self::T30_WEIGHT_FACTOR,
            self::TIER_T4 => self::T40_WEIGHT_FACTOR,
            self::TIER_T4_1 => self::T41_WEIGHT_FACTOR,
            self::TIER_T4_2 => self::T42_WEIGHT_FACTOR,
            self::TIER_T4_3 => self::T43_WEIGHT_FACTOR,
            self::TIER_T4_4 => self::T44_WEIGHT_FACTOR,
            self::TIER_T5 => self::T50_WEIGHT_FACTOR,
            self::TIER_T5_1 => self::T51_WEIGHT_FACTOR,
            self::TIER_T5_2 => self::T52_WEIGHT_FACTOR,
            self::TIER_T5_3 => self::T53_WEIGHT_FACTOR,
            self::TIER_T5_4 => self::T54_WEIGHT_FACTOR,
            self::TIER_T6 => self::T60_WEIGHT_FACTOR,
            self::TIER_T6_1 => self::T61_WEIGHT_FACTOR,
            self::TIER_T6_2 => self::T62_WEIGHT_FACTOR,
            self::TIER_T6_3 => self::T63_WEIGHT_FACTOR,
            self::TIER_T6_4 => self::T64_WEIGHT_FACTOR,
            self::TIER_T7 => self::T70_WEIGHT_FACTOR,
            self::TIER_T7_1 => self::T71_WEIGHT_FACTOR,
            self::TIER_T7_2 => self::T72_WEIGHT_FACTOR,
            self::TIER_T7_3 => self::T73_WEIGHT_FACTOR,
            self::TIER_T7_4 => self::T74_WEIGHT_FACTOR,
            self::TIER_T8 => self::T80_WEIGHT_FACTOR,
            self::TIER_T8_1 => self::T81_WEIGHT_FACTOR,
            self::TIER_T8_2 => self::T82_WEIGHT_FACTOR,
            self::TIER_T8_3 => self::T83_WEIGHT_FACTOR,
            self::TIER_T8_4 => self::T84_WEIGHT_FACTOR,
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
            self::TIER_T4_4 => self::T44_NUTRITION_FACTOR,
            self::TIER_T5 => self::T50_NUTRITION_FACTOR,
            self::TIER_T5_1 => self::T51_NUTRITION_FACTOR,
            self::TIER_T5_2 => self::T52_NUTRITION_FACTOR,
            self::TIER_T5_3 => self::T53_NUTRITION_FACTOR,
            self::TIER_T5_4 => self::T54_NUTRITION_FACTOR,
            self::TIER_T6 => self::T60_NUTRITION_FACTOR,
            self::TIER_T6_1 => self::T61_NUTRITION_FACTOR,
            self::TIER_T6_2 => self::T62_NUTRITION_FACTOR,
            self::TIER_T6_3 => self::T63_NUTRITION_FACTOR,
            self::TIER_T6_4 => self::T64_NUTRITION_FACTOR,
            self::TIER_T7 => self::T70_NUTRITION_FACTOR,
            self::TIER_T7_1 => self::T71_NUTRITION_FACTOR,
            self::TIER_T7_2 => self::T72_NUTRITION_FACTOR,
            self::TIER_T7_3 => self::T73_NUTRITION_FACTOR,
            self::TIER_T7_4 => self::T74_NUTRITION_FACTOR,
            self::TIER_T8 => self::T80_NUTRITION_FACTOR,
            self::TIER_T8_1 => self::T81_NUTRITION_FACTOR,
            self::TIER_T8_2 => self::T82_NUTRITION_FACTOR,
            self::TIER_T8_3 => self::T83_NUTRITION_FACTOR,
            self::TIER_T8_4 => self::T84_NUTRITION_FACTOR,
            default => throw new \InvalidArgumentException('wrong Nutrition Factor in Item Entity')
        };
    }
}
