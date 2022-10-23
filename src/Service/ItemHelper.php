<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

class ItemHelper
{
    public function __construct(
        private ApiService $apiService
    ) {
    }

    public function getWarriorItems(): array
    {
        return [
            ApiService::ITEM_WARRIOR_HELMET => $this->apiService->getBlackMarketItem(ApiService::ITEM_WARRIOR_HELMET),
            ApiService::ITEM_WARRIOR_ARMOR => $this->apiService->getBlackMarketItem(ApiService::ITEM_WARRIOR_ARMOR),
            ApiService::ITEM_WARRIOR_BOOTS => $this->apiService->getBlackMarketItem(ApiService::ITEM_WARRIOR_BOOTS),
            ApiService::ITEM_WARRIOR_SWORD => $this->apiService->getBlackMarketItem(ApiService::ITEM_WARRIOR_SWORD),
            ApiService::ITEM_WARRIOR_AXE => $this->apiService->getBlackMarketItem(ApiService::ITEM_WARRIOR_AXE),
            ApiService::ITEM_WARRIOR_MACE => $this->apiService->getBlackMarketItem(ApiService::ITEM_WARRIOR_MACE),
            ApiService::ITEM_WARRIOR_HAMMER => $this->apiService->getBlackMarketItem(ApiService::ITEM_WARRIOR_HAMMER),
            ApiService::ITEM_WARRIOR_WAR_GLOVE => $this->apiService->getBlackMarketItem(
                ApiService::ITEM_WARRIOR_WAR_GLOVE
            ),
            ApiService::ITEM_WARRIOR_CROSSBOW => $this->apiService->getBlackMarketItem(
                ApiService::ITEM_WARRIOR_CROSSBOW
            ),
            ApiService::ITEM_WARRIOR_SHIELD => $this->apiService->getBlackMarketItem(ApiService::ITEM_WARRIOR_SHIELD),
        ];
    }

    public function getMageItems(): array
    {
        return [
            ApiService::ITEM_MAGE_HELMET => $this->apiService->getBlackMarketItem(ApiService::ITEM_MAGE_HELMET),
            ApiService::ITEM_MAGE_ARMOR => $this->apiService->getBlackMarketItem(ApiService::ITEM_MAGE_ARMOR),
            ApiService::ITEM_MAGE_BOOTS => $this->apiService->getBlackMarketItem(ApiService::ITEM_MAGE_BOOTS),
            ApiService::ITEM_MAGE_FIRE_STAFF => $this->apiService->getBlackMarketItem(ApiService::ITEM_MAGE_FIRE_STAFF),
            ApiService::ITEM_MAGE_HOLY_STAFF => $this->apiService->getBlackMarketItem(ApiService::ITEM_MAGE_HOLY_STAFF),
            ApiService::ITEM_MAGE_ARCANE_STAFF => $this->apiService->getBlackMarketItem(
                ApiService::ITEM_MAGE_ARCANE_STAFF
            ),
            ApiService::ITEM_MAGE_FROST_STAFF => $this->apiService->getBlackMarketItem(
                ApiService::ITEM_MAGE_FROST_STAFF
            ),
            ApiService::ITEM_MAGE_CURSE_STAFF => $this->apiService->getBlackMarketItem(
                ApiService::ITEM_MAGE_CURSE_STAFF
            ),
            ApiService::ITEM_MAGE_TOME_STAFF => $this->apiService->getBlackMarketItem(ApiService::ITEM_MAGE_TOME_STAFF),
        ];
    }

    public function getHunterItems(): array
    {
        return [
            ApiService::ITEM_HUNTER_HELMET => $this->apiService->getBlackMarketItem(ApiService::ITEM_HUNTER_HELMET),
            ApiService::ITEM_HUNTER_ARMOR => $this->apiService->getBlackMarketItem(ApiService::ITEM_HUNTER_ARMOR),
            ApiService::ITEM_HUNTER_BOOTS => $this->apiService->getBlackMarketItem(ApiService::ITEM_HUNTER_BOOTS),
            ApiService::ITEM_HUNTER_BOW => $this->apiService->getBlackMarketItem(ApiService::ITEM_HUNTER_BOW),
            ApiService::ITEM_HUNTER_SPEAR => $this->apiService->getBlackMarketItem(ApiService::ITEM_HUNTER_SPEAR),
            ApiService::ITEM_HUNTER_NATURE_STAFF => $this->apiService->getBlackMarketItem(
                ApiService::ITEM_HUNTER_NATURE_STAFF
            ),
            ApiService::ITEM_HUNTER_DAGGER => $this->apiService->getBlackMarketItem(ApiService::ITEM_HUNTER_DAGGER),
            ApiService::ITEM_HUNTER_QUARTERSTAFF => $this->apiService->getBlackMarketItem(
                ApiService::ITEM_HUNTER_QUARTERSTAFF
            ),
            ApiService::ITEM_HUNTER_TORCH => $this->apiService->getBlackMarketItem(ApiService::ITEM_HUNTER_TORCH),
        ];
    }


}