<?php

declare(strict_types=1);

namespace MZierdt\Albion\factories;

use MZierdt\Albion\HttpClient;
use MZierdt\Albion\Service\ApiService;
use Psr\Container\ContainerInterface;

class ApiServiceFactory
{
    public function __invoke(ContainerInterface $container): ApiService
    {
        return new ApiService($container->get(HttpClient::class));
    }
}