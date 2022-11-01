<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use PDO;
use PDOException;

class UploadRepository
{
    public function __construct(
        private PDO $pdoConnection
    ) {
    }

    public function loadResourcesIntoDatabase(array $resourceInformation): void
    {
        $query = <<<SQL
            INSERT INTO albion_db.resource
            (`tier`, `name`, `city`, `realName`, `sellOrderPrice`, `sellOrderPriceDate`,`buyOrderPrice`, `buyOrderPriceDate`, `bonusCity`)
            VALUES (?,?,?,?,?,?,?,?,?)
SQL;
        foreach ($resourceInformation as $information) {
            $this->inputInformation($this->pdoConnection, $query, [
                $information['tier'],
                $information['name'],
                $information['city'],
                $information['realName'],
                $information['sellOrderPrice'],
                $information['sellOrderPriceDate'],
                $information['buyOrderPrice'],
                $information['buyOrderPriceDate'],
                $information['bonusCity'],
            ]);
        }
    }

    private function inputInformation(PDO $PDO, string $query, array $data): void
    {
        if ($query !== '') {
            $stmt = $PDO->prepare($query);
            $stmt->execute(array_values($data));
        } else {
            echo 'Query is not set';
            throw new PDOException();
        }
    }

    public function reloadUpdatedPricesResources(array $resourceInformation): void
    {
        foreach ($resourceInformation as $information) {
            if ($information['sellOrderPrice'] !== '0') {
                $statement = $this->pdoConnection->prepare(
                    <<<SQL
                    UPDATE albion_db.resource
                    SET `sellOrderPrice` = :sellOrderPrice,
                        `sellOrderPriceDate` = :sellOrderPriceDate
                    WHERE albion_db.resource.name = :name
                    AND albion_db.resource.tier = :tier
                    AND albion_db.resource.city = :city
SQL
                );
                $statement->bindParam(':sellOrderPrice', $information['sellOrderPrice']);
                $statement->bindParam(':sellOrderPriceDate', $information['sellOrderPriceDate']);
                $statement->bindParam(':name', $information['name']);
                $statement->bindParam(':tier', $information['tier']);
                $statement->bindParam(':city', $information['city']);
                $statement->execute();
            }
            if ($information['buyOrderPrice'] !== '0') {
                $statement = $this->pdoConnection->prepare(
                    <<<SQL
                    UPDATE albion_db.resource
                    SET `buyOrderPrice` = :buyOrderPrice,
                        `buyOrderPriceDate` = :buyOrderPriceDate
                    WHERE albion_db.resource.name = :name
                    AND albion_db.resource.tier = :tier
                    AND albion_db.resource.city = :city
SQL
                );
                $statement->bindParam(':buyOrderPrice', $information['buyOrderPrice']);
                $statement->bindParam(':buyOrderPriceDate', $information['buyOrderPriceDate']);
                $statement->bindParam(':name', $information['name']);
                $statement->bindParam(':tier', $information['tier']);
                $statement->bindParam(':city', $information['city']);
                $statement->execute();
            }
        }
    }

    public function reloadUpdatedPricesJournals(array $journalInformation): void
    {
        foreach ($journalInformation as $information) {
            if ($information['sellOrderPrice'] !== 0) {
                $statement = $this->pdoConnection->prepare(
                    <<<SQL
                    UPDATE albion_db.journals
                    SET `sellOrderPrice` = :sellOrderPrice,
                        `sellOrderPriceDate` = :sellOrderPriceDate
                    WHERE albion_db.journals.name = :name
                    AND albion_db.journals.tier = :tier
                    AND albion_db.journals.city = :city
                    AND albion_db.journals.class = :classGroup
SQL
                );
                $statement->bindParam(':sellOrderPrice', $information['sellOrderPrice']);
                $statement->bindParam(':sellOrderPriceDate', $information['sellOrderPriceDate']);
                $statement->bindParam(':name', $information['name']);
                $statement->bindParam(':tier', $information['tier']);
                $statement->bindParam(':city', $information['city']);
                $statement->bindParam(':classGroup', $information['class']);
                $statement->execute();
            }
            if ($information['buyOrderPrice'] !== 0) {
                $statement = $this->pdoConnection->prepare(
                    <<<SQL
                    UPDATE albion_db.journals
                    SET `buyOrderPrice` = :buyOrderPrice,
                        `buyOrderPriceDate` = :buyOrderPriceDate
                    WHERE albion_db.journals.name = :name
                    AND albion_db.journals.tier = :tier
                    AND albion_db.journals.city = :city
                    AND albion_db.journals.class = :classGroup
SQL
                );
                $statement->bindParam(':buyOrderPrice', $information['buyOrderPrice']);
                $statement->bindParam(':buyOrderPriceDate', $information['buyOrderPriceDate']);
                $statement->bindParam(':name', $information['name']);
                $statement->bindParam(':tier', $information['tier']);
                $statement->bindParam(':city', $information['city']);
                $statement->bindParam(':classGroup', $information['class']);
                $statement->execute();
            }
        }
    }

    public function reloadUpdatedPricesItems(array $itemInformation): void
    {
        foreach ($itemInformation as $information) {
            if ($information['sellOrderPrice'] !== 0) {
                $statement = $this->pdoConnection->prepare(
                    <<<SQL
                    UPDATE albion_db.items
                    SET `sellOrderPrice` = :sellOrderPrice,
                        `sellOrderPriceDate` = :sellOrderPriceDate
                    WHERE albion_db.items.name = :name
                    AND albion_db.items.tier = :tier
                    AND albion_db.items.city = :city
SQL
                );
                $statement->bindParam(':sellOrderPrice', $information['sellOrderPrice']);
                $statement->bindParam(':sellOrderPriceDate', $information['sellOrderPriceDate']);
                $statement->bindParam(':name', $information['name']);
                $statement->bindParam(':tier', $information['tier']);
                $statement->bindParam(':city', $information['city']);
                $statement->execute();
            }
            if ($information['buyOrderPrice'] !== 0) {
                $statement = $this->pdoConnection->prepare(
                    <<<SQL
                    UPDATE albion_db.items
                    SET `buyOrderPrice` = :buyOrderPrice,
                        `buyOrderPriceDate` = :buyOrderPriceDate
                    WHERE albion_db.items.name = :name
                    AND albion_db.items.tier = :tier
                    AND albion_db.items.city = :city
SQL
                );
                $statement->bindParam(':buyOrderPrice', $information['buyOrderPrice']);
                $statement->bindParam(':buyOrderPriceDate', $information['buyOrderPriceDate']);
                $statement->bindParam(':name', $information['name']);
                $statement->bindParam(':tier', $information['tier']);
                $statement->bindParam(':city', $information['city']);
                $statement->execute();
            }
        }
    }

    public function loadItemsIntoDatabase(array $itemArrayByClass): void
    {
        $query = <<<SQL
            INSERT INTO albion_db.items
            (`tier`,
             `name`,
             `weaponGroup`,
             `realName`,
             `class`,
             `city`,
             `quality`,
             `sellOrderPrice`,
             `sellOrderPriceDate`,
             `buyOrderPrice`,
             `buyOrderPriceDate`,
             `primaryResource`,
             `primaryResourceAmount`,
             `secondaryResource`,
             `secondaryResourceAmount`,
             `bonusCity`,
             `fameFactor`)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
SQL;
        foreach ($itemArrayByClass as $item) {
            $this->inputInformation($this->pdoConnection, $query, [
                $item['tier'],
                $item['name'],
                $item['weaponGroup'],
                $item['realName'],
                $item['class'],
                $item['city'],
                $item['quality'],
                $item['sellOrderPrice'],
                $item['sellOrderPriceDate'],
                $item['buyOrderPrice'],
                $item['buyOrderPriceDate'],
                $item['primaryResource'],
                $item['primaryResourceAmount'],
                $item['secondaryResource'],
                $item['secondaryResourceAmount'],
                $item['bonusCity'],
                $item['fameFactor'],
            ]);
        }
    }


    public function emptyDb()
    {
        $query = <<<SQL
        TRUNCATE TABLE albion_db.items
SQL;
        $this->pdoConnection->query($query);

        $query = <<<SQL
        TRUNCATE TABLE albion_db.resource
 SQL;
        $this->pdoConnection->query($query);

        $query = <<<SQL
        TRUNCATE TABLE albion_db.journals
 SQL;
        $this->pdoConnection->query($query);
    }

    public function loadJournalsIntoDatabase(array $journalInformation): void
    {
        $query = <<<SQL
            INSERT INTO albion_db.journals
            (`tier`, `name`, `city`, `fameToFill`, `sellOrderPrice`, `sellOrderPriceDate`,`buyOrderPrice`, `buyOrderPriceDate`, `weight`, `fillStatus`, `class` )
            VALUES (?,?,?,?,?,?,?,?,?,?,?)
SQL;
        foreach ($journalInformation as $information) {
            $this->inputInformation($this->pdoConnection, $query, [
                $information['tier'],
                $information['name'],
                $information['city'],
                $information['fameToFill'],
                $information['sellOrderPrice'],
                $information['sellOrderPriceDate'],
                $information['buyOrderPrice'],
                $information['buyOrderPriceDate'],
                $information['weight'],
                $information['fillStatus'],
                $information['class'],
            ]);
        }
    }
}
