<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use PDO;

class JournalRepository
{
    public function __construct(private PDO $pdoConnection)
    {
    }

    public function getItemsFromCity(string $city)
    {

        }
}