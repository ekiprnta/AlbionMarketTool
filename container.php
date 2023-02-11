<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManager;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\ServiceManager\ServiceManager;
use MZierdt\Albion\commands\UpdateItemsCommand;
use MZierdt\Albion\commands\UpdateJournalsCommand;
use MZierdt\Albion\commands\UpdateMaterialsCommand;
use MZierdt\Albion\commands\UpdateRawResourcesCommand;
use MZierdt\Albion\commands\UpdateResourcesCommand;
use MZierdt\Albion\factories\EntityManagerFactory;
use MZierdt\Albion\factories\TwigEnvironmentFactory;
use MZierdt\Albion\Handler\AdminHandler;
use MZierdt\Albion\Handler\BlackMarketCraftingHandler;
use MZierdt\Albion\Handler\BlackMarketTransportingHandler;
use MZierdt\Albion\Handler\EnchantingHandler;
use MZierdt\Albion\Handler\listDataHandler;
use MZierdt\Albion\Handler\RefiningHandler;
use MZierdt\Albion\Handler\TransmutationHandler;
use MZierdt\Albion\HttpClient;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\repositories\ItemRepositoryFactory;
use MZierdt\Albion\repositories\JournalRepository;
use MZierdt\Albion\repositories\JournalRepositoryFactory;
use MZierdt\Albion\repositories\MaterialRepository;
use MZierdt\Albion\repositories\MaterialRepositoryFactory;
use MZierdt\Albion\repositories\ResourceRepository;
use MZierdt\Albion\repositories\ResourceRepositoryFactory;
use MZierdt\Albion\Service\ApiService;
use MZierdt\Albion\Service\BlackMarketCraftingHelper;
use MZierdt\Albion\Service\BlackMarketCraftingService;
use MZierdt\Albion\Service\BlackMarketTransportingHelper;
use MZierdt\Albion\Service\BlackMarketTransportingService;
use MZierdt\Albion\Service\ConfigService;
use MZierdt\Albion\Service\EnchantingHelper;
use MZierdt\Albion\Service\EnchantingService;
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
                    ConfigService::class
                ],
                RefiningHelper::class => [],
                RefiningService::class => [
                    ResourceRepository::class,
                    RefiningHelper::class,
                ],
                TierService::class => [],
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
                    ResourceRepository::class,
                    TransmutationHelper::class,
                    ConfigService::class,
                    GlobalDiscountService::class,
                ],
                TransmutationHelper::class => [],
                EnchantingHandler::class => [
                    Environment::class,
                    EnchantingService::class
                ],
                EnchantingService::class => [
                    MaterialRepository::class,
                    ItemRepository::class,
                    EnchantingHelper::class
                ],
                EnchantingHelper::class => [],
                AdminHandler::class => [
                    Environment::class,
                ],
                GlobalDiscountService::class => [
                    ApiService::class
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
                    ResourceRepository::class,
                    ConfigService::class,
                    UploadHelper::class,
                ],
                UpdateMaterialsCommand::class => [
                    ApiService::class,
                    MaterialRepository::class,
                    UploadHelper::class,
                ]
            ]
        ],
    ],
    'abstract_factories' => [ConfigAbstractFactory::class],
    'factories' => [
        EntityManager::class => EntityManagerFactory::class,
        Environment::class => TwigEnvironmentFactory::class,
        ResourceRepository::class => ResourceRepositoryFactory::class,
        ItemRepository::class => ItemRepositoryFactory::class,
        JournalRepository::class => JournalRepositoryFactory::class,
        MaterialRepository::class => MaterialRepositoryFactory::class,
        'abstract_factories' => [ConfigAbstractFactory::class],
    ],
]);
return $serviceManager;
