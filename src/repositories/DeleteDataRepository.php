<?php

namespace MZierdt\Albion\repositories;

use PDO;

class DeleteDataRepository
{
    public function __construct(private PDO $pdoConnection)
    {
    }


    public function deleteDataFromTable(string $table)
    {
        $query = sprintf('TRUNCATE TABLE %s', $table);

        $this->pdoConnection->query($query);
    }
}