<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

class ItemRepository
{
    public function __construct(
        private PDO $pdoConnection
    )
    {
    }
}
