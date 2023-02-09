<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class MaterialRepositoryFactory
{

    public function __invoke(ContainerInterface $container): MaterialRepository
    {
        return new MaterialRepository($container->get(EntityManager::class));
    }
}