<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use PDO;

class UploadRepository
{
    public function __construct(
        private PDO $pdoConnection
    ) {
    }

    public function updatePricesFromItem(array $itemInformation): void
    {
        foreach ($itemInformation as $item) {
            if ($item['sellOrderPrice'] !== 0) {
                $statement = $this->pdoConnection->prepare(
                    <<<SQL
                INSERT INTO `items` (`tier`,
             `name`,
             `weaponGroup`,
             `realName`,
             `class`,
             `city`,
             `quality`,
             `sellOrderPrice`,
             `sellOrderPriceDate`,
             `primaryResource`,
             `primaryResourceAmount`,
             `secondaryResource`,
             `secondaryResourceAmount`,
             `bonusCity`,
             `fameFactor`)
            VALUES (:tier, :name, :weaponGroup, :realName, :class, :city, :quality, :sellOrderPrice, :sellOrderPriceDate, :primaryResource, :primaryResourceAmount, :secondaryResource, :secondaryResourceAmount, :bonusCity, :fameFactor)
            ON DUPLICATE KEY UPDATE `sellOrderPrice`  = :sellOrderPrice, `sellOrderPriceDate` = :sellOrderPriceDate;
SQL
                );
                $statement->bindParam(':tier', $item['tier']);
                $statement->bindParam(':name', $item['name']);
                $statement->bindParam(':weaponGroup', $item['weaponGroup']);
                $statement->bindParam(':realName', $item['realName']);
                $statement->bindParam(':class', $item['class']);
                $statement->bindParam(':city', $item['city']);
                $statement->bindParam(':quality', $item['quality']);
                $statement->bindParam(':sellOrderPrice', $item['sellOrderPrice']);
                $statement->bindParam(':sellOrderPriceDate', $item['sellOrderPriceDate']);
                $statement->bindParam(':primaryResource', $item['primaryResource']);
                $statement->bindParam(':primaryResourceAmount', $item['primaryResourceAmount']);
                $statement->bindParam(':secondaryResource', $item['secondaryResource']);
                $statement->bindParam(':secondaryResourceAmount', $item['secondaryResourceAmount']);
                $statement->bindParam(':bonusCity', $item['bonusCity']);
                $statement->bindParam(':fameFactor', $item['fameFactor']);
                $statement->execute();
            }
            if ($item['buyOrderPrice'] !== 0) {
                $statement = $this->pdoConnection->prepare(
                    <<<SQL
                INSERT INTO `items` (`tier`,
             `name`,
             `weaponGroup`,
             `city`,
             `buyOrderPrice`,
             `buyOrderPriceDate`)
            VALUES (:tier, :name, :weaponGroup, :city, :buyOrderPrice, :buyOrderPriceDate)
            ON DUPLICATE KEY UPDATE `buyOrderPrice`  = :buyOrderPrice, `buyOrderPriceDate` = :buyOrderPriceDate;
SQL
                );
                $statement->bindParam(':tier', $item['tier']);
                $statement->bindParam(':name', $item['name']);
                $statement->bindParam(':weaponGroup', $item['weaponGroup']);
                $statement->bindParam(':city', $item['city']);
                $statement->bindParam(':buyOrderPrice', $item['buyOrderPrice']);
                $statement->bindParam(':buyOrderPriceDate', $item['buyOrderPriceDate']);
                $statement->execute();
            }
        }
    }

    public function updatePricesFromResources(array $resourceInformation): void
    {
        foreach ($resourceInformation as $resource) {
            if ($resource['sellOrderPrice'] !== 0) {
                $statement = $this->pdoConnection->prepare(
                    <<<SQL
                INSERT INTO `resource` (`tier`, `name`, `city`, `realName`, `sellOrderPrice`, `sellOrderPriceDate`, `bonusCity`)
            VALUES (:tier, :name, :city, :realName, :sellOrderPrice, :sellOrderPriceDate, :bonusCity)
            ON DUPLICATE KEY UPDATE `sellOrderPrice`  = :sellOrderPrice, `sellOrderPriceDate` = :sellOrderPriceDate;
SQL
                );
                $statement->bindParam(':tier', $resource['tier']);
                $statement->bindParam(':name', $resource['name']);
                $statement->bindParam(':city', $resource['city']);
                $statement->bindParam(':realName', $resource['realName']);
                $statement->bindParam(':sellOrderPrice', $resource['sellOrderPrice']);
                $statement->bindParam(':sellOrderPriceDate', $resource['sellOrderPriceDate']);
                $statement->bindParam(':bonusCity', $resource['bonusCity']);
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

    public function updatePricesFromJournals(array $journalInformation): void
    {
        foreach ($journalInformation as $journals) {
            if ($journals['sellOrderPrice'] !== 0) {
                $statement = $this->pdoConnection->prepare(
                    <<<SQL
                INSERT INTO `journals` (`tier`, `name`, `city`, `fameToFill`, `sellOrderPrice`, `sellOrderPriceDate`, `weight`, `fillStatus`, `class` )
            VALUES (:tier, :name, :city, :fameToFill, :sellOrderPrice, :sellOrderPriceDate, :weight, :fillStatus, :class)
            ON DUPLICATE KEY UPDATE `sellOrderPrice`  = :sellOrderPrice, `sellOrderPriceDate` = :sellOrderPriceDate;
SQL
                );
                $statement->bindParam(':tier', $journals['tier']);
                $statement->bindParam(':name', $journals['name']);
                $statement->bindParam(':city', $journals['city']);
                $statement->bindParam(':fameToFill', $journals['fameToFill']);
                $statement->bindParam(':sellOrderPrice', $journals['sellOrderPrice']);
                $statement->bindParam(':sellOrderPriceDate', $journals['sellOrderPriceDate']);
                $statement->bindParam(':weight', $journals['weight']);
                $statement->bindParam(':fillStatus', $journals['fillStatus']);
                $statement->bindParam(':class', $journals['class']);
                $statement->execute();
            }
            if ($journals['buyOrderPrice'] !== 0) {
                $statement = $this->pdoConnection->prepare(
                    <<<SQL
            INSERT INTO `journals` (`tier`, `name`, `city`, `buyOrderPrice`, `buyOrderPriceDate`, `fillStatus`)
            VALUES (:tier, :name, :city, :buyOrderPrice, :buyOrderPriceDate, :fillStatus)
            ON DUPLICATE KEY UPDATE `buyOrderPrice`  = :buyOrderPrice, `buyOrderPriceDate` = :buyOrderPriceDate;
SQL
                );
                $statement->bindParam(':tier', $journals['tier']);
                $statement->bindParam(':name', $journals['name']);
                $statement->bindParam(':city', $journals['city']);
                $statement->bindParam(':buyOrderPrice', $journals['buyOrderPrice']);
                $statement->bindParam(':buyOrderPriceDate', $journals['buyOrderPriceDate']);
                $statement->bindParam(':fillStatus', $journals['fillStatus']);
                $statement->execute();
            }
        }
    }
}
