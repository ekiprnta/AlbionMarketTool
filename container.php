<?php

declare(strict_types=1);

use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\ServiceManager\ServiceManager;
use MZierdt\Albion\factories\TwigEnvironmentFactory;
use MZierdt\Albion\Handler\ShowResourcePriceHandler;
use MZierdt\Albion\HttpClient;
use MZierdt\Albion\Service\ApiService;
use Twig\Environment;

$serviceManager = new ServiceManager([
    'services' => [
        'config' => [
            ConfigAbstractFactory::class => [
                ApiService::class => [
                    HttpClient::class
                ],
                HttpClient::class => [],
                ShowResourcePriceHandler::class => [
                    Environment::class,
                    ApiService::class
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
