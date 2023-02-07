<?php

declare(strict_types=1);

namespace MZierdt\Albion\factories;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

class EntityManagerFactory
{
    public function __invoke(): EntityManager
    {
        $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: [__DIR__ . '/../../src'],
            isDevMode: true
        );

        $connectionParams = [
            'dbname' => 'albion_db',
            'user' => 'flyka',
            'password' => 'qwert',
            'host' => 'db_local',
            'port' => '3306',
            'driver' => 'pdo_mysql'
        ];

        $connection = DriverManager::getConnection($connectionParams);

        return new  EntityManager($connection, $config);
    }
}