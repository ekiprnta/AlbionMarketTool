<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class ItemRepositoryFactory
{
    public function __invoke(ContainerInterface $container): ItemRepository
    {
        return new ItemRepository($container->get(EntityManager::class));
    }
}
