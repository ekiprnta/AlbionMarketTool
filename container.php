<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManager;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\ServiceManager\ServiceManager;
use MZierdt\Albion\AlbionDataAPI\ItemApiService;
use MZierdt\Albion\AlbionDataAPI\MaterialsApiService;
use MZierdt\Albion\AlbionDataAPI\MiscApiService;
use MZierdt\Albion\AlbionDataAPI\ResourceApiService;
use MZierdt\Albion\AlbionMarket\BlackMarketCraftingService;
use MZierdt\Albion\AlbionMarket\BlackMarketTransportingService;
use MZierdt\Albion\AlbionMarket\EnchantingService;
use MZierdt\Albion\AlbionMarket\ListDataHelper;
use MZierdt\Albion\AlbionMarket\ListDataService;
use MZierdt\Albion\AlbionMarket\NoSpecCraftingService;
use MZierdt\Albion\AlbionMarket\RefiningService;
use MZierdt\Albion\AlbionMarket\TransmutationService;
use MZierdt\Albion\commands\UpdateBmCraftingCommand;
use MZierdt\Albion\commands\UpdateBmTransportCommand;
use MZierdt\Albion\commands\UpdateEnchantingCommand;
use MZierdt\Albion\commands\UpdateItemsCommand;
use MZierdt\Albion\commands\UpdateJournalsCommand;
use MZierdt\Albion\commands\UpdateMaterialsCommand;
use MZierdt\Albion\commands\UpdateNoSpecCraftingCommand;
use MZierdt\Albion\commands\UpdateRefiningCommand;
use MZierdt\Albion\commands\UpdateResourcesCommand;
use MZierdt\Albion\commands\UpdateTransmutationCommand;
use MZierdt\Albion\factories\EntityManagerFactory;
use MZierdt\Albion\factories\TwigEnvironmentFactory;
use MZierdt\Albion\Handler\AdminHandler;
use MZierdt\Albion\Handler\BlackMarketCraftingHandler;
use MZierdt\Albion\Handler\BlackMarketTransportingHandler;
use MZierdt\Albion\Handler\EnchantingHandler;
use MZierdt\Albion\Handler\ListDataHandler;
use MZierdt\Albion\Handler\NoSpecCraftingHandler;
use MZierdt\Albion\Handler\RefiningHandler;
use MZierdt\Albion\Handler\TransmutationHandler;
use MZierdt\Albion\HttpClient;
use MZierdt\Albion\repositories\AdvancedRepository\BlackMarketCraftingRepository;
use MZierdt\Albion\repositories\AdvancedRepository\BlackMarketTransportingRepository;
use MZierdt\Albion\repositories\AdvancedRepository\EnchantingRepository;
use MZierdt\Albion\repositories\AdvancedRepository\NoSpecRepository;
use MZierdt\Albion\repositories\AdvancedRepository\RefiningRepository;
use MZierdt\Albion\repositories\AdvancedRepository\TransmutationRepository;
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
                BlackMarketCraftingService::class => [],
                ConfigService::class => [],
                RefiningService::class => [],
                TierService::class => [],
                NoSpecCraftingHandler::class => [Environment::class, NoSpecRepository::class],
                NoSpecCraftingService::class => [],
                NoSpecRepository::class => [
                    EntityManager::class,
                ],
                BlackMarketCraftingRepository::class => [
                    EntityManager::class,
                ],
                BlackMarketTransportingService::class => [],
                BlackMarketTransportingRepository::class => [EntityManager::class],
                BlackMarketTransportingHandler::class => [
                    Environment::class,
                    BlackMarketTransportingRepository::class,
                ],
                BlackMarketCraftingHandler::class => [
                    Environment::class,
                    BlackMarketCraftingRepository::class,
                    BlackMarketCraftingService::class,
                ],
                RefiningHandler::class => [
                    Environment::class,
                    RefiningRepository::class,
                ],
                RefiningRepository::class => [
                    EntityManager::class,
                ],
                TransmutationHandler::class => [
                    Environment::class,
                    TransmutationRepository::class,
                ],
                TransmutationService::class => [],
                EnchantingHandler::class => [
                    Environment::class,
                    EnchantingRepository::class
                ],
                TransmutationRepository::class => [EntityManager::class],
                EnchantingService::class => [],
                AdminHandler::class => [
                    Environment::class,
                ],
                EnchantingRepository::class => [
                    EntityManager::class,
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
                ],
                UpdateBmTransportCommand::class => [
                    BlackMarketTransportingService::class,
                    BlackMarketTransportingRepository::class,
                    ItemRepository::class,
                    ConfigService::class,
                ],
                UpdateRefiningCommand::class => [
                    RefiningService::class,
                    RefiningRepository::class,
                    ResourceRepository::class,
                ],
                UpdateTransmutationCommand::class => [
                    TransmutationService::class,
                    TransmutationRepository::class,
                    ResourceRepository::class,
                    ConfigService::class,
                    GlobalDiscountService::class
                ],
                UpdateEnchantingCommand::class => [
                    EnchantingService::class,
                    EnchantingRepository::class,
                    ItemRepository::class,
                    MaterialRepository::class
                ],
                UpdateNoSpecCraftingCommand::class => [
                    NoSpecCraftingService::class,
                    NoSpecRepository::class,
                    ItemRepository::class,
                    MaterialRepository::class,
                ],
                UpdateBmCraftingCommand::class => [
                    BlackMarketCraftingService::class,
                    BlackMarketCraftingRepository::class,
                    ItemRepository::class,
                    ResourceRepository::class,
                    JournalRepository::class,
                    ConfigService::class,
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
