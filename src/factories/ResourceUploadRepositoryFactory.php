<?php

declare(strict_types=1);

namespace MZierdt\Albion\factories;

use MZierdt\Albion\repositories\ResourceUploadRepository;
use MZierdt\Albion\Service\DatabaseService;
use Psr\Container\ContainerInterface;

class ResourceUploadRepositoryFactory
{
    public function __invoke(ContainerInterface $container): ResourceUploadRepository
    {
        $password = 'qwert';
        return new ResourceUploadRepository(DatabaseService::getConnection($password));
    }
}