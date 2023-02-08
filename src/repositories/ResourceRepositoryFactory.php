<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class ResourceRepositoryFactory
{
    public function __invoke(ContainerInterface $container): ResourceRepository
    {
        return new ResourceRepository($container->get(EntityManager::class));
    }
}
