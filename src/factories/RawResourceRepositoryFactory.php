<?php

namespace MZierdt\Albion\factories;

use MZierdt\Albion\repositories\RawResourceRepository;
use MZierdt\Albion\Service\DatabaseService;
use Psr\Container\ContainerInterface;

class RawResourceRepositoryFactory
{
    public function __invoke(ContainerInterface $container): RawResourceRepository
    {
        $password = 'qwert';
        return new RawResourceRepository(DatabaseService::getConnection($password));
    }
}
