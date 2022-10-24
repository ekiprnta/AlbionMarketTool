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

    public function getItemsFromCity(string $city)
    {
        return $this->getItemsFromDb($city);
    }

    private function getItemsFromDb(string $city): array
    {
        $statement = $this->pdoConnection->prepare(
            <<<SQL
            SELECT * 
            FROM albion_db.items
            WHERE albion_db.items.bonusCity = :city
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
}
