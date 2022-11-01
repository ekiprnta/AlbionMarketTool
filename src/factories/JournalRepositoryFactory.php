<?php

declare(strict_types=1);

namespace MZierdt\Albion\factories;

use MZierdt\Albion\repositories\JournalRepository;
use MZierdt\Albion\Service\DatabaseService;
use Psr\Container\ContainerInterface;

class JournalRepositoryFactory
{
    public function __invoke(ContainerInterface $container): JournalRepository
    {
        $password = 'qwert';
        return new JournalRepository(DatabaseService::getConnection($password));
    }
}
