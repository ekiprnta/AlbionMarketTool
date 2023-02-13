<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class JournalRepositoryFactory
{
    public function __invoke(ContainerInterface $container): JournalRepository
    {
        return new JournalRepository($container->get(EntityManager::class));
    }
}
