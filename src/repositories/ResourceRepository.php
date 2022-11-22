<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use MZierdt\Albion\Entity\ResourceEntity;
use PDO;

class ResourceRepository
{
    public function __construct(
        private PDO $pdoConnection
    ) {
    }

    public function getResourcesByCity(string $city): array
    {
        $statement = $this->pdoConnection->prepare(
            <<<SQL
            SELECT * 
            FROM albion_db.resource
            WHERE albion_db.resource.city = :city
SQL
        );

        $statement->bindParam(':city', $city);
        $statement->execute();

        $resourceArray = [];
        $emptyArray = [];
        foreach ($statement->getIterator() as $resourceInformation) {
            if (empty($resourceInformation['sellOrderPriceDate'])) {
                $emptyArray[]= [
                    $resourceInformation['tier'],
                    $resourceInformation['city'],
                    $resourceInformation['name'],
                    $resourceInformation['bonusCity']
                ];
            } else {
                $resourceArray[] = new ResourceEntity($resourceInformation);
            }
        }
        return $resourceArray;
    }
}
