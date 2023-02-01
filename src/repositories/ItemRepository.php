<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use MZierdt\Albion\Entity\ItemEntity;
use PDO;

class ItemRepository
{
    public function __construct(
        private PDO $pdoConnection
    ) {
    }

    public function getItemsByLocation(string $city): array
    {
        $statement = $this->pdoConnection->prepare(
            <<<SQL
            SELECT *
            FROM albion_db.items
            WHERE albion_db.items.city = :city
SQL
        );
        $statement->bindParam(':city', $city);
        $statement->execute();

        $itemsArray = [];
        foreach ($statement->getIterator() as $itemInformation) {
            $itemsArray[] = new ItemEntity($itemInformation);
        }
        return $itemsArray;
    }

    public function getBlackMarketItemsFromCity(string $city): array
    {
        $statement = $this->pdoConnection->prepare(
            <<<SQL
            SELECT * 
            FROM albion_db.items
            WHERE albion_db.items.bonusCity = :city
            AND  city = :bm
SQL
        );

        $statement->bindParam(':city', $city);
        $blackMarketCity = 'Black Market';
        $statement->bindParam(':bm', $blackMarketCity);
        $statement->execute();
        $itemsArray = [];
        foreach ($statement->getIterator() as $itemInformation) {
            $itemsArray[] = new ItemEntity($itemInformation);
        }
        return $itemsArray;
    }

    public function updatePricesFromItem(array $itemInformation): void
    {
        foreach ($itemInformation as $item) {
            $statement = $this->pdoConnection->prepare(
                <<<SQL
            INSERT INTO `items` (`tier`,
             `name`,
             `weaponGroup`,
             `realName`,
             `class`,
             `city`,
             `quality`,
             `primaryResource`,
             `primaryResourceAmount`,
             `secondaryResource`,
             `secondaryResourceAmount`,
             `bonusCity`,
             `fameFactor`)
            VALUES (:tier, :name, :weaponGroup, :realName, :class, :city, :quality, :primaryResource, :primaryResourceAmount, :secondaryResource, :secondaryResourceAmount, :bonusCity, :fameFactor)
            ON DUPLICATE KEY UPDATE `tier` = `tier`;
SQL
            );
            $statement->bindParam(':tier', $item['tier']);
            $statement->bindParam(':name', $item['name']);
            $statement->bindParam(':weaponGroup', $item['weaponGroup']);
            $statement->bindParam(':realName', $item['realName']);
            $statement->bindParam(':class', $item['class']);
            $statement->bindParam(':city', $item['city']);
            $statement->bindParam(':quality', $item['quality']);
            $statement->bindParam(':primaryResource', $item['primaryResource']);
            $statement->bindParam(':primaryResourceAmount', $item['primaryResourceAmount']);
            $statement->bindParam(':secondaryResource', $item['secondaryResource']);
            $statement->bindParam(':secondaryResourceAmount', $item['secondaryResourceAmount']);
            $statement->bindParam(':bonusCity', $item['bonusCity']);
            $statement->bindParam(':fameFactor', $item['fameFactor']);
            $statement->execute();

            if ($item['sellOrderPrice'] !== 0) {
                $statement = $this->pdoConnection->prepare(
                    <<<SQL
                INSERT INTO `items` (`tier`,
             `name`,
             `weaponGroup`,
             `city`,
             `sellOrderPrice`,
             `sellOrderPriceDate`)
            VALUES (:tier, :name, :weaponGroup, :city, :sellOrderPrice, :sellOrderPriceDate)
            ON DUPLICATE KEY UPDATE `sellOrderPrice`  = :sellOrderPrice, `sellOrderPriceDate` = :sellOrderPriceDate;
SQL
                );
                $statement->bindParam(':tier', $item['tier']);
                $statement->bindParam(':name', $item['name']);
                $statement->bindParam(':weaponGroup', $item['weaponGroup']);
                $statement->bindParam(':city', $item['city']);
                $statement->bindParam(':sellOrderPrice', $item['sellOrderPrice']);
                $statement->bindParam(':sellOrderPriceDate', $item['sellOrderPriceDate']);
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
}
