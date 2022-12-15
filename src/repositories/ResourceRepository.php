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
        foreach ($statement->getIterator() as $resourceInformation) {
            $resourceArray[] = new ResourceEntity($resourceInformation);
        }
        return $resourceArray;
    }

    public function getResourcesByBonusCity(string $city): array
    {
        $statement = $this->pdoConnection->prepare(
            <<<SQL
            SELECT * 
            FROM albion_db.resource
            WHERE albion_db.resource.bonusCity = :city
            AND albion_db.resource.city = :city
SQL
        );

        $statement->bindParam(':city', $city);
        $statement->execute();

        $resourceArray = [];
        foreach ($statement->getIterator() as $resourceInformation) {
            $resourceArray[] = new ResourceEntity($resourceInformation);
        }
        return $resourceArray;
    }

    public function updatePricesFromResources(array $resourceInformation): void
    {
        foreach ($resourceInformation as $resource) {
            $statement = $this->pdoConnection->prepare(
                <<<SQL
                INSERT INTO `resource` (`tier`, `name`, `city`, `realName`, `bonusCity`)
            VALUES (:tier, :name, :city, :realName, :bonusCity)
            ON DUPLICATE KEY UPDATE `tier` = `tier`
SQL
            );
            $statement->bindParam(':tier', $resource['tier']);
            $statement->bindParam(':name', $resource['name']);
            $statement->bindParam(':city', $resource['city']);
            $statement->bindParam(':realName', $resource['realName']);
            $statement->bindParam(':bonusCity', $resource['bonusCity']);
            $statement->execute();
            if ($resource['sellOrderPrice'] !== 0) {
                $statement = $this->pdoConnection->prepare(
                    <<<SQL
                INSERT INTO `resource` (`tier`,
             `name`,
             `city`,
             `sellOrderPrice`,
             `sellOrderPriceDate`)
            VALUES (:tier, :name, :city, :sellOrderPrice, :sellOrderPriceDate)
            ON DUPLICATE KEY UPDATE `sellOrderPrice`  = :sellOrderPrice, `sellOrderPriceDate` = :sellOrderPriceDate;
SQL
                );
                $statement->bindParam(':tier', $resource['tier']);
                $statement->bindParam(':name', $resource['name']);
                $statement->bindParam(':city', $resource['city']);
                $statement->bindParam(':sellOrderPrice', $resource['sellOrderPrice']);
                $statement->bindParam(':sellOrderPriceDate', $resource['sellOrderPriceDate']);
                $statement->execute();
            }
            if ($resource['buyOrderPrice'] !== 0) {
                $statement = $this->pdoConnection->prepare(
                    <<<SQL
                INSERT INTO `resource` (`tier`,
             `name`,
             `city`,
             `buyOrderPrice`,
             `buyOrderPriceDate`)
            VALUES (:tier, :name, :city, :buyOrderPrice, :buyOrderPriceDate)
            ON DUPLICATE KEY UPDATE `buyOrderPrice`  = :buyOrderPrice, `buyOrderPriceDate` = :buyOrderPriceDate;
SQL
                );
                $statement->bindParam(':tier', $resource['tier']);
                $statement->bindParam(':name', $resource['name']);
                $statement->bindParam(':city', $resource['city']);
                $statement->bindParam(':buyOrderPrice', $resource['buyOrderPrice']);
                $statement->bindParam(':buyOrderPriceDate', $resource['buyOrderPriceDate']);
                $statement->execute();
            }
        }
    }
}
