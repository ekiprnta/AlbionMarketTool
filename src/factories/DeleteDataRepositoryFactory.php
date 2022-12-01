<?php

namespace MZierdt\Albion\factories;

use MZierdt\Albion\repositories\DeleteDataRepository;
use MZierdt\Albion\Service\DatabaseService;
use Psr\Container\ContainerInterface;

class DeleteDataRepositoryFactory
{
    public function __invoke(ContainerInterface $container): DeleteDataRepository
    {
        $password = 'qwert';
        return new DeleteDataRepository(DatabaseService::getConnection($password));
    }
}