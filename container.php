<?php

declare(strict_types=1);

use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\ServiceManager\ServiceManager;
use MZierdt\Albion\factories\TwigEnvironmentFactory;
use MZierdt\Albion\Handler\BlackMarketHandler;
use MZierdt\Albion\Handler\CalculateInfoHandler;
use MZierdt\Albion\Handler\ShowResourcePriceHandler;
use MZierdt\Albion\HttpClient;
use MZierdt\Albion\repositories\ResourceRepositoryUpload;
use MZierdt\Albion\Service\ApiService;
use Twig\Environment;

$serviceManager = new ServiceManager([
    'services' => [
        'config' => [
            ConfigAbstractFactory::class => [
                HttpClient::class => [],
                ApiService::class => [
                    HttpClient::class
                ],
                ResourceRepositoryUpload::class => [
                    ApiService::class
                ],
                ShowResourcePriceHandler::class => [
                    Environment::class,
                    ResourceRepositoryUpload::class
                ],
                CalculateInfoHandler::class => [
                  Environment::class
                ],
                BlackMarketHandler::class => [
                    Environment::class
                ],
            ]
        ],
    ],
    'abstract_factories' => [ConfigAbstractFactory::class],
    'factories' => [
        Environment::class => TwigEnvironmentFactory::class,
        'abstract_factories' => [ConfigAbstractFactory::class],
    ],
]);
return $serviceManager;
