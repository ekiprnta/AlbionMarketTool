<?php

declare(strict_types=1);

use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\ServiceManager\ServiceManager;
use MZierdt\Albion\commands\DeleteDataCommand;
use MZierdt\Albion\commands\UpdateItemsCommand;
use MZierdt\Albion\commands\UpdateJournalsCommand;
use MZierdt\Albion\commands\UpdateRawResourcesCommand;
use MZierdt\Albion\commands\UpdateResourcesCommand;
use MZierdt\Albion\factories\DeleteDataRepositoryFactory;
use MZierdt\Albion\factories\ItemRepositoryFactory;
use MZierdt\Albion\factories\JournalRepositoryFactory;
use MZierdt\Albion\factories\RawResourceRepositoryFactory;
use MZierdt\Albion\factories\ResourceRepositoryFactory;
use MZierdt\Albion\factories\TwigEnvironmentFactory;
use MZierdt\Albion\Handler\AdminHandler;
use MZierdt\Albion\Handler\BlackMarketCraftingHandler;
use MZierdt\Albion\Handler\BlackMarketTransportingHandler;
use MZierdt\Albion\Handler\CapesCraftingHandler;
use MZierdt\Albion\Handler\listDataHandler;
use MZierdt\Albion\Handler\RefiningHandler;
use MZierdt\Albion\Handler\TransmutationHandler;
use MZierdt\Albion\HttpClient;
use MZierdt\Albion\repositories\DeleteDataRepository;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\repositories\JournalRepository;
use MZierdt\Albion\repositories\RawResourceRepository;
use MZierdt\Albion\repositories\ResourceRepository;
use MZierdt\Albion\Service\ApiService;
use MZierdt\Albion\Service\BlackMarketCraftingHelper;
use MZierdt\Albion\Service\BlackMarketCraftingService;
use MZierdt\Albion\Service\BlackMarketTransportingHelper;
use MZierdt\Albion\Service\BlackMarketTransportingService;
use MZierdt\Albion\Service\CapesCraftingService;
use MZierdt\Albion\Service\ConfigService;
use MZierdt\Albion\Service\GlobalDiscountService;
use MZierdt\Albion\Service\ListDataHelper;
use MZierdt\Albion\Service\ListDataService;
use MZierdt\Albion\Service\RefiningHelper;
use MZierdt\Albion\Service\RefiningService;
use MZierdt\Albion\Service\TierService;
use MZierdt\Albion\Service\TransmutationHelper;
use MZierdt\Albion\Service\TransmutationService;
use MZierdt\Albion\Service\UploadHelper;
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
                    ListDataService::class
                ],
                ListDataService::class => [
                    ResourceRepository::class,
                    RawResourceRepository::class,
                    ListDataHelper::class
                ],
                ListDataHelper::class => [],
                UploadHelper::class => [
                    TierService::class
                ],
                BlackMarketCraftingHelper::class => [],
                ConfigService::class => [],
                BlackMarketCraftingService::class => [
                    ItemRepository::class,
                    ResourceRepository::class,
                    JournalRepository::class,
                    BlackMarketCraftingHelper::class,
                ],
                RefiningHelper::class => [],
                RefiningService::class => [
                    ResourceRepository::class,
                    RawResourceRepository::class,
                    RefiningHelper::class,
                ],
                TierService::class => [],
                CapesCraftingHandler::class => [Environment::class, CapesCraftingService::class],
                CapesCraftingService::class => [],
                BlackMarketTransportingHelper::class => [],
                BlackMarketTransportingService::class => [
                    ItemRepository::class,
                    BlackMarketTransportingHelper::class,
                    ConfigService::class,
                ],
                BlackMarketTransportingHandler::class => [
                    Environment::class,
                    BlackMarketTransportingService::class
                ],
                BlackMarketCraftingHandler::class => [
                    Environment::class,
                    BlackMarketCraftingService::class,
                ],
                RefiningHandler::class => [
                    Environment::class,
                    RefiningService::class,
                ],
                TransmutationHandler::class => [
                    Environment::class,
                    TransmutationService::class,
                ],
                TransmutationService::class => [
                    RawResourceRepository::class,
                    TransmutationHelper::class,
                    ConfigService::class,
                    GlobalDiscountService::class,
                ],
                TransmutationHelper::class => [],
                AdminHandler::class => [
                    Environment::class,
                ],
                GlobalDiscountService::class => [
                    ApiService::class
                ],
                DeleteDataCommand::class => [
                    DeleteDataRepository::class
                ],
                UpdateJournalsCommand::class => [
                    ApiService::class,
                    JournalRepository::class,
                    ConfigService::class,
                    UploadHelper::class,
                ],
                UpdateItemsCommand::class => [
                    ApiService::class,
                    ItemRepository::class,
                    ConfigService::class,
                    UploadHelper::class,
                ],
                UpdateResourcesCommand::class => [
                    ApiService::class,
                    ResourceRepository::class,
                    ConfigService::class,
                    UploadHelper::class,
                ],
                UpdateRawResourcesCommand::class => [
                    ApiService::class,
                    RawResourceRepository::class,
                    ConfigService::class,
                    UploadHelper::class,
                ],
            ]
        ],
    ],
    'abstract_factories' => [ConfigAbstractFactory::class],
    'factories' => [
        Environment::class => TwigEnvironmentFactory::class,
        ResourceRepository::class => ResourceRepositoryFactory::class,
        RawResourceRepository::class => RawResourceRepositoryFactory::class,
        ItemRepository::class => ItemRepositoryFactory::class,
        JournalRepository::class => JournalRepositoryFactory::class,
        DeleteDataRepository::class => DeleteDataRepositoryFactory::class,
        'abstract_factories' => [ConfigAbstractFactory::class],
    ],
]);
return $serviceManager;
