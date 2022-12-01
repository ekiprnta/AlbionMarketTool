<?php

declare(strict_types=1);

use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\ServiceManager\ServiceManager;
use MZierdt\Albion\commands\DeleteDataCommand;
use MZierdt\Albion\commands\UpdateItemsCommand;
use MZierdt\Albion\commands\UpdateJournalsCommand;
use MZierdt\Albion\commands\UpdateResourcesCommand;
use MZierdt\Albion\factories\DeleteDataRepositoryFactory;
use MZierdt\Albion\factories\ItemRepositoryFactory;
use MZierdt\Albion\factories\JournalRepositoryFactory;
use MZierdt\Albion\factories\ResourceRepositoryFactory;
use MZierdt\Albion\factories\TwigEnvironmentFactory;
use MZierdt\Albion\Handler\AdminHandler;
use MZierdt\Albion\Handler\BlackMarketCraftingHandler;
use MZierdt\Albion\Handler\BlackMarketTransportingHandler;
use MZierdt\Albion\Handler\listDataHandler;
use MZierdt\Albion\repositories\DeleteDataRepository;
use MZierdt\Albion\repositories\JournalRepository;
use MZierdt\Albion\Service\BlackMarketCraftingService;
use MZierdt\Albion\Service\BlackMarketTransportingService;
use MZierdt\Albion\Service\UploadHelper;
use MZierdt\Albion\HttpClient;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\repositories\ResourceRepository;
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
                listDataHandler::class => [
                    Environment::class,
                    ItemRepository::class,
                    ResourceRepository::class,
                    UploadHelper::class
                ],
                BlackMarketCraftingService::class => [
                    ItemRepository::class,
                    ResourceRepository::class,
                    JournalRepository::class
                ],
                BlackMarketTransportingService::class => [
                    ItemRepository::class
                ],
                BlackMarketTransportingHandler::class => [
                    Environment::class,
                    BlackMarketTransportingService::class
                ],
                BlackMarketCraftingHandler::class => [
                    Environment::class,
                    BlackMarketCraftingService::class,
                ],
                AdminHandler::class => [
                    Environment::class,
                ],
                DeleteDataCommand::class => [
                    DeleteDataRepository::class
                ],
                UpdateJournalsCommand::class => [
                    ApiService::class,
                    JournalRepository::class,
                ],
                UpdateItemsCommand::class => [
                    ApiService::class,
                    ItemRepository::class,
                ],
                UpdateResourcesCommand::class => [
                    ApiService::class,
                    ResourceRepository::class,
                ],
            ]
        ],
    ],
    'abstract_factories' => [ConfigAbstractFactory::class],
    'factories' => [
        Environment::class => TwigEnvironmentFactory::class,
        ResourceRepository::class => ResourceRepositoryFactory::class,
        ItemRepository::class => ItemRepositoryFactory::class,
        JournalRepository::class => JournalRepositoryFactory::class,
        DeleteDataRepository::class => DeleteDataRepositoryFactory::class,
        'abstract_factories' => [ConfigAbstractFactory::class],
    ],
]);
return $serviceManager;
