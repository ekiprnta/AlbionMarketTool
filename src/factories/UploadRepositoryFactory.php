<?php

declare(strict_types=1);

namespace MZierdt\Albion\factories;

use MZierdt\Albion\repositories\UploadRepository;
use MZierdt\Albion\Service\DatabaseService;
use Psr\Container\ContainerInterface;

class UploadRepositoryFactory
{
    public function __invoke(ContainerInterface $container): UploadRepository
    {
        $password = 'qwert';
        return new UploadRepository(DatabaseService::getConnection($password));
    }
}
