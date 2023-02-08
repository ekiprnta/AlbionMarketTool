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
#[Table(name: 'resources')]
class ResourceEntity extends AlbionItemEntity
{
    final public const RESOURCE_METAL_BAR = 'metalBar';
    final public const RESOURCE_PLANKS = 'planks';
    final public const RESOURCE_CLOTH = 'cloth';
    final public const RESOURCE_LEATHER = 'leather';
    final public const RESOURCE_STONE_BLOCK = 'stoneBLock';

    private const T20_WEIGHT_FACTOR = 0.23;
    private const T30_WEIGHT_FACTOR = 0.34;
    private const T40_WEIGHT_FACTOR = 0.51;
    private const T41_WEIGHT_FACTOR = 0.51;
    private const T42_WEIGHT_FACTOR = 0.51;
    private const T43_WEIGHT_FACTOR = 0.51;
    private const T44_WEIGHT_FACTOR = 0.51;
    private const T50_WEIGHT_FACTOR = 0.76;
    private const T51_WEIGHT_FACTOR = 0.76;
    private const T52_WEIGHT_FACTOR = 0.76;
    private const T53_WEIGHT_FACTOR = 0.76;
    private const T54_WEIGHT_FACTOR = 0.76;
    private const T60_WEIGHT_FACTOR = 1.14;
    private const T61_WEIGHT_FACTOR = 1.14;
    private const T62_WEIGHT_FACTOR = 1.14;
    private const T63_WEIGHT_FACTOR = 1.14;
    private const T64_WEIGHT_FACTOR = 1.14;
    private const T70_WEIGHT_FACTOR = 1.71;
    private const T71_WEIGHT_FACTOR = 1.71;
    private const T72_WEIGHT_FACTOR = 1.71;
    private const T73_WEIGHT_FACTOR = 1.71;
    private const T74_WEIGHT_FACTOR = 1.71;
    private const T80_WEIGHT_FACTOR = 2.56;
    private const T81_WEIGHT_FACTOR = 2.56;
    private const T82_WEIGHT_FACTOR = 2.56;
    private const T83_WEIGHT_FACTOR = 2.56;
    private const T84_WEIGHT_FACTOR = 2.56;

    #[Column(type: 'string', nullable: true)]
    private ?string $bonusCity = null;
    #[Id, Column(type: 'boolean')]
    private bool $raw = false;

    public function setBonusCity(?string $bonusCity): void
    {
        $this->bonusCity = $bonusCity;
    }

    public function setRaw(bool $raw): void
    {
        $this->raw = $raw;
    }

    public function isRaw(): bool
    {
        return $this->raw;
    }

    public function getBonusCity(): ?string
    {
        return $this->bonusCity;
    }
}
