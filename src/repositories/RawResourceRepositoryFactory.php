<?php

namespace MZierdt\Albion\repositories;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class RawResourceRepositoryFactory
{
    public function __invoke(ContainerInterface $container): RawResourceRepository
    {
        return new RawResourceRepository($container->get(EntityManager::class));
    }
}
