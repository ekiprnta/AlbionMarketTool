<?php

namespace MZierdt\Albion\repositories;

use MZierdt\Albion\Entity\ResourceEntity;
use PDO;

class RawResourceRepository
{
    public function __construct(private PDO $pdoConnection)
    {
    }

    public function getResourcesByCity(string $city): array
    {
        $statement = $this->pdoConnection->prepare(
            <<<SQL
            SELECT * 
            FROM albion_db.rawResource
            WHERE albion_db.rawResource.city = :city
SQL
        );

        $statement->bindParam(':city', $city);
        $statement->execute();

        $rawResourceArray = [];
        foreach ($statement->getIterator() as $rawResourceInformation) {
            $rawResourceArray[] = new ResourceEntity($rawResourceInformation);
        }
        return $rawResourceArray;
    }

    public function updatePricesFromResources(array $rawResourceInformation): void
    {
        foreach ($rawResourceInformation as $rawResource) {
            $statement = $this->pdoConnection->prepare(
                <<<SQL
                INSERT INTO `rawResource` (`tier`, `name`, `city`, `realName`, `bonusCity`)
            VALUES (:tier, :name, :city, :realName, :bonusCity)
            ON DUPLICATE KEY UPDATE `tier` = `tier`
SQL
            );
            $statement->bindParam(':tier', $rawResource['tier']);
            $statement->bindParam(':name', $rawResource['name']);
            $statement->bindParam(':city', $rawResource['city']);
            $statement->bindParam(':realName', $rawResource['realName']);
            $statement->bindParam(':bonusCity', $rawResource['bonusCity']);
            $statement->execute();
            if ($rawResource['sellOrderPrice'] !== 0) {
                $statement = $this->pdoConnection->prepare(
                    <<<SQL
                INSERT INTO `rawResource` (`tier`,
             `name`,
             `city`,
             `sellOrderPrice`,
             `sellOrderPriceDate`)
            VALUES (:tier, :name, :city, :sellOrderPrice, :sellOrderPriceDate)
            ON DUPLICATE KEY UPDATE `sellOrderPrice`  = :sellOrderPrice, `sellOrderPriceDate` = :sellOrderPriceDate;
SQL
                );
                $statement->bindParam(':tier', $rawResource['tier']);
                $statement->bindParam(':name', $rawResource['name']);
                $statement->bindParam(':city', $rawResource['city']);
                $statement->bindParam(':sellOrderPrice', $rawResource['sellOrderPrice']);
                $statement->bindParam(':sellOrderPriceDate', $rawResource['sellOrderPriceDate']);
                $statement->execute();
            }
            if ($rawResource['buyOrderPrice'] !== 0) {
                $statement = $this->pdoConnection->prepare(
                    <<<SQL
                INSERT INTO `rawResource` (`tier`,
             `name`,
             `city`,
             `buyOrderPrice`,
             `buyOrderPriceDate`)
            VALUES (:tier, :name, :city, :buyOrderPrice, :buyOrderPriceDate)
            ON DUPLICATE KEY UPDATE `buyOrderPrice`  = :buyOrderPrice, `buyOrderPriceDate` = :buyOrderPriceDate;
SQL
                );
                $statement->bindParam(':tier', $rawResource['tier']);
                $statement->bindParam(':name', $rawResource['name']);
                $statement->bindParam(':city', $rawResource['city']);
                $statement->bindParam(':buyOrderPrice', $rawResource['buyOrderPrice']);
                $statement->bindParam(':buyOrderPriceDate', $rawResource['buyOrderPriceDate']);
                $statement->execute();
            }
        }
    }
}