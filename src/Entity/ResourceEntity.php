<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity;

class ResourceEntity
{
    private int $tier;
    private string $name;
    private string $city;
    private int $buyOrderPrice;
    private int $sellOrderPrice;
}