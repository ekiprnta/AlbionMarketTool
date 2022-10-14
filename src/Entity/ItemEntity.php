<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity;

class ItemEntity
{
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

    private const RESOURCE_STONES = 0;
    private const RESOURCE_PLANKS = 1;
    private const RESOURCE_BARS = 2;
    private const RESOURCE_CLOTH = 3;
    private const RESOURCE_LEATHER = 4;

    private int $tier;
    private string $name;
    private int $sellPriceBlackMarket;
    private int $primaryResourceAmount;
    private int $secondaryResourceAmount;
}