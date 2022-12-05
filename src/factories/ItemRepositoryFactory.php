<?php

declare(strict_types=1);

namespace MZierdt\Albion\factories;

use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\Service\DatabaseService;
use Psr\Container\ContainerInterface;

class ItemRepositoryFactory
{
    public function __invoke(ContainerInterface $container): ItemRepository
    {
        $password = 'qwert';

        $pdoConnection = DatabaseService::getConnection($password);
        return new ItemRepository($pdoConnection);
    }
}
