<?php

declare(strict_types=1);

namespace MZierdt\Albion\factories;

use MZierdt\Albion\repositories\ResourceRepository;
use MZierdt\Albion\Service\DatabaseService;
use Psr\Container\ContainerInterface;

class ResourceRepositoryFactory
{

    public function __invoke(ContainerInterface $container): ResourceRepository
    {
        $password = 'qwert';
        return new ResourceRepository(DatabaseService::getConnection($password));
    }
}