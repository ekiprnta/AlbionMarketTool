<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use PDO;

class ResourceRepository
{
    public function __construct(
        private PDO $pdoConnection
    )
    {
    }

    public function getResourcesByCity(string $city)
    {
        $cityResources = $this->getResourcesFromDb($city);
    }

    private function getResourcesFromDb(string $city)
    {

    }

}
