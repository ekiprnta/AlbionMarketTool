<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use MZierdt\Albion\Entity\JournalEntity;
use PDO;

class JournalRepository
{
    public function __construct(private PDO $pdoConnection)
    {
    }

    public function getJournalsFromCity(string $city): array
    {
        $statement = $this->pdoConnection->prepare(
            <<<SQL
            SELECT * 
            FROM albion_db.journals
            WHERE albion_db.journals.city = :city
SQL
        );

        $statement->bindParam(':city', $city);
        $statement->execute();
        $journalsArray = [];
        foreach ($statement->getIterator() as $journalInformation) {
            $journalsArray[] = new JournalEntity($journalInformation);
        }
        return $journalsArray;
    }

    public function updatePricesFromJournals(array $journalInformation): void
    {
        foreach ($journalInformation as $journals) {
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
            if ($journals['sellOrderPrice'] !== 0) {
                $statement = $this->pdoConnection->prepare(
                    <<<SQL
            INSERT INTO `journals` (`tier`, `name`, `city`, `sellOrderPrice`, `sellOrderPriceDate`, `fillStatus`)
            VALUES (:tier, :name, :city, :sellOrderPrice, :sellOrderPriceDate, :fillStatus)
            ON DUPLICATE KEY UPDATE `sellOrderPrice`  = :sellOrderPrice, `sellOrderPriceDate` = :sellOrderPriceDate;
SQL
                );
                $statement->bindParam(':tier', $journals['tier']);
                $statement->bindParam(':name', $journals['name']);
                $statement->bindParam(':city', $journals['city']);
                $statement->bindParam(':fillStatus', $journals['fillStatus']);
                $statement->bindParam(':sellOrderPrice', $journals['sellOrderPrice']);
                $statement->bindParam(':sellOrderPriceDate', $journals['sellOrderPriceDate']);
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
                $statement->bindParam(':fillStatus', $journals['fillStatus']);
                $statement->bindParam(':buyOrderPrice', $journals['buyOrderPrice']);
                $statement->bindParam(':buyOrderPriceDate', $journals['buyOrderPriceDate']);
                $statement->execute();
            }
        }
    }
}
