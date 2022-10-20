<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use League\Csv\Writer;
use MZierdt\Albion\Service\ApiService;
use PDO;
use PDOException;

class ResourceUploadRepository
{
    public function __construct(
        private PDO $pdoConnection
    ) {
    }

    public function loadDataIntoDatabase(array $resourceInformation)
    {
        $query = <<<SQL
INSERT INTO albion_db.resource
(`tier`, `name`, `city`, `sellOrderPrice`, `sellOrderPriceDate`,`buyOrderPrice`, `buyOrderPriceDate`, `bonusCity`)
VALUES (?,?,?,?,?,?,?,?)
SQL;
        foreach ($resourceInformation as $information) {
            $this->inputInformation($this->pdoConnection, $query, [
                $information['tier'],
                $information['name'],
                $information['city'],
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
}
