<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

use PDO;
use PDOException;

class DatabaseService
{
    private const HOST = 'db_local'; //db dev_db_1 10.0.3.2
    private const PORT = '3306';
    private const DB = 'albion_db';
    private const USER = 'flyka';
    private const DSN = 'mysql:host=' . self::HOST . ';port=' . self::PORT . ';dbname=' . self::DB;

    public static function getConnection(string $password): PDO
    {
        try {
            return new PDO(self::DSN, self::USER, 'qwert');
        } catch (PDOException $PDOException) {
            echo 'cant connect to database with: ' .  self::DSN . PHP_EOL . self::USER . PHP_EOL . $password . PHP_EOL;
            throw $PDOException;
        }
    }
}
