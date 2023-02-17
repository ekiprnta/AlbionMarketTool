<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManager;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\ServiceManager\ServiceManager;
use MZierdt\Albion\AlbionDataAPI\ItemApiService;
use MZierdt\Albion\AlbionDataAPI\MaterialsApiService;
use MZierdt\Albion\AlbionDataAPI\MiscApiService;
use MZierdt\Albion\AlbionDataAPI\ResourceApiService;
use MZierdt\Albion\AlbionMarket\BlackMarketCraftingHelper;
use MZierdt\Albion\AlbionMarket\BlackMarketCraftingService;
use MZierdt\Albion\AlbionMarket\BlackMarketTransportingHelper;
use MZierdt\Albion\AlbionMarket\BlackMarketTransportingService;
use MZierdt\Albion\AlbionMarket\EnchantingHelper;
use MZierdt\Albion\AlbionMarket\EnchantingService;
use MZierdt\Albion\AlbionMarket\ListDataHelper;
use MZierdt\Albion\AlbionMarket\ListDataService;
use MZierdt\Albion\AlbionMarket\NoSpecCraftingHelper;
use MZierdt\Albion\AlbionMarket\NoSpecCraftingService;
use MZierdt\Albion\AlbionMarket\RefiningHelper;
use MZierdt\Albion\AlbionMarket\RefiningService;
use MZierdt\Albion\AlbionMarket\TransmutationHelper;
use MZierdt\Albion\AlbionMarket\TransmutationService;
use MZierdt\Albion\commands\UpdateItemsCommand;
use MZierdt\Albion\commands\UpdateJournalsCommand;
use MZierdt\Albion\commands\UpdateMaterialsCommand;
use MZierdt\Albion\commands\UpdateResourcesCommand;
use MZierdt\Albion\factories\EntityManagerFactory;
use MZierdt\Albion\factories\TwigEnvironmentFactory;
use MZierdt\Albion\Handler\AdminHandler;
use MZierdt\Albion\Handler\BlackMarketCraftingHandler;
use MZierdt\Albion\Handler\BlackMarketTransportingHandler;
use MZierdt\Albion\Handler\CapesCraftingHandler;
use MZierdt\Albion\Handler\EnchantingHandler;
use MZierdt\Albion\Handler\ListDataHandler;
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
use MZierdt\Albion\Service\ConfigService;
use MZierdt\Albion\Service\GlobalDiscountService;
use MZierdt\Albion\Service\TierService;
use MZierdt\Albion\Service\UploadHelper;
use Twig\Environment;

$serviceManager = new ServiceManager([
    'services' => [
        'config' => [
            ConfigAbstractFactory::class => [
                HttpClient::class => [],
                ItemApiService::class => [HttpClient::class],
                MaterialsApiService::class => [HttpClient::class],
                ResourceApiService::class => [HttpClient::class],
                MiscApiService::class => [HttpClient::class],
                ListDataHandler::class => [
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
                CapesCraftingHandler::class => [Environment::class, NoSpecCraftingService::class],
                NoSpecCraftingService::class => [
                    ItemRepository::class,
                    MaterialRepository::class,
                    NoSpecCraftingHelper::class
                ],
                NoSpecCraftingHelper::class => [],
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
                    MiscApiService::class
                ],
                UpdateJournalsCommand::class => [
                    MiscApiService::class,
                    JournalRepository::class,
                    ConfigService::class,
                    UploadHelper::class,
                ],
                UpdateItemsCommand::class => [
                    ItemApiService::class,
                    ItemRepository::class,
                    ConfigService::class,
                    UploadHelper::class,
                ],
                UpdateResourcesCommand::class => [
                    ResourceApiService::class,
                    ResourceRepository::class,
                    ConfigService::class,
                    UploadHelper::class,
                ],
                UpdateMaterialsCommand::class => [
                    MaterialsApiService::class,
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
