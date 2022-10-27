<?php

declare(strict_types=1);

use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\ServiceManager\ServiceManager;
use MZierdt\Albion\factories\ItemRepositoryFactory;
use MZierdt\Albion\factories\ResourceRepositoryFactory;
use MZierdt\Albion\factories\UploadRepositoryFactory;
use MZierdt\Albion\factories\TwigEnvironmentFactory;
use MZierdt\Albion\Handler\AdminHandler;
use MZierdt\Albion\Handler\BlackMarketHandler;
use MZierdt\Albion\Handler\CalculateInfoHandler;
use MZierdt\Albion\Handler\listDataHandler;
use MZierdt\Albion\Service\CalculatorService;
use MZierdt\Albion\Service\UploadService;
use MZierdt\Albion\HttpClient;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\repositories\ResourceRepository;
use MZierdt\Albion\repositories\UploadRepository;
use MZierdt\Albion\Service\ApiService;
use MZierdt\Albion\Service\ItemHelper;
use Twig\Environment;

$serviceManager = new ServiceManager([
    'services' => [
        'config' => [
            ConfigAbstractFactory::class => [
                HttpClient::class => [],
                ApiService::class => [
                    HttpClient::class
                ],
                listDataHandler::class => [
                    Environment::class,
                    ItemRepository::class,
                    ResourceRepository::class,
                    UploadService::class
                ],
                UploadService::class => [
                    ApiService::class,
                    UploadRepository::class,
                    ItemHelper::class
                ],
                CalculatorService::class => [
                ItemRepository::class,
                ResourceRepository::class
                ],
                CalculateInfoHandler::class => [
                    Environment::class
                ],
                BlackMarketHandler::class => [
                    Environment::class,
                    CalculatorService::class,
                    UploadService::class,
                ],
                ItemHelper::class => [
                    ApiService::class
                ],
                AdminHandler::class => [
                    Environment::class,
                    UploadService::class,
                    UploadRepository::class
                ]
            ]
        ],
    ],
    'abstract_factories' => [ConfigAbstractFactory::class],
    'factories' => [
        Environment::class => TwigEnvironmentFactory::class,
        UploadRepository::class => UploadRepositoryFactory::class,
        ResourceRepository::class => ResourceRepositoryFactory::class,
        ItemRepository::class => ItemRepositoryFactory::class,
        'abstract_factories' => [ConfigAbstractFactory::class],
    ],
]);
return $serviceManager;
