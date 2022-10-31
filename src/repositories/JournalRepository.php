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
            $itemsArray = [];
            foreach ($statement->getIterator() as $journalInformation) {
                $journalsArray[] = new JournalEntity($journalInformation);
            }
            return $journalsArray;
        }
    }
}