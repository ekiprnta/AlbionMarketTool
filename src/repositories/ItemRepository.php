<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

class ItemRepository
{
    private const PATH_TO_CSV_WARRIOR = __DIR__ . '/../../assets/warrior.csv';
    private const PATH_TO_CSV_MAGE = __DIR__ . '/../../assets/mage.csv';
    private const PATH_TO_CSV_HUNTER = __DIR__ . '/../../assets/hunter.csv';
    private const WARRIOR = 'warrior';
    private const MAGE = 'mage';
    private const HUNTER = 'hunter';

    public function getItemsAsItemEntityFromCity(string $city)
    {

    }
}