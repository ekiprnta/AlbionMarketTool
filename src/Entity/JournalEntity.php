<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity;

use DateTimeImmutable;

class JournalEntity
{
    private string $tier;
    private string $name;
    private string $city;
    private int $fameToFill;
    private int $sellOrderPrice;
    private DateTimeImmutable $sellOrderPriceDate;
    private int $buyOrderPrice;
    private DateTimeImmutable $buyOrderPriceDate;
    private int $weight;
    private string $fillStatus; //full empty
    private string $weaponGroup; //warrior mage
}