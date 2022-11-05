<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

use MZierdt\Albion\Entity\ItemEntity;

class ItemHelper
{
    public function __construct(
        private ApiService $apiService
    ) {
    }

    public function getWarriorItems(): array
    {
        return [
            ItemEntity::ITEM_WARRIOR_HELMET => $this->apiService->getBlackMarketItem(ItemEntity::ITEM_WARRIOR_HELMET),
            ItemEntity::ITEM_WARRIOR_ARMOR => $this->apiService->getBlackMarketItem(ItemEntity::ITEM_WARRIOR_ARMOR),
            ItemEntity::ITEM_WARRIOR_BOOTS => $this->apiService->getBlackMarketItem(ItemEntity::ITEM_WARRIOR_BOOTS),
            ItemEntity::ITEM_WARRIOR_SWORD => $this->apiService->getBlackMarketItem(ItemEntity::ITEM_WARRIOR_SWORD),
            ItemEntity::ITEM_WARRIOR_AXE => $this->apiService->getBlackMarketItem(ItemEntity::ITEM_WARRIOR_AXE),
            ItemEntity::ITEM_WARRIOR_MACE => $this->apiService->getBlackMarketItem(ItemEntity::ITEM_WARRIOR_MACE),
            ItemEntity::ITEM_WARRIOR_HAMMER => $this->apiService->getBlackMarketItem(ItemEntity::ITEM_WARRIOR_HAMMER),
            ItemEntity::ITEM_WARRIOR_WAR_GLOVE => $this->apiService->getBlackMarketItem(
                ItemEntity::ITEM_WARRIOR_WAR_GLOVE
            ),
            ItemEntity::ITEM_WARRIOR_CROSSBOW => $this->apiService->getBlackMarketItem(
                ItemEntity::ITEM_WARRIOR_CROSSBOW
            ),
            ItemEntity::ITEM_WARRIOR_SHIELD => $this->apiService->getBlackMarketItem(ItemEntity::ITEM_WARRIOR_SHIELD),
        ];
    }

    public function getMageItems(): array
    {
        return [
            ItemEntity::ITEM_MAGE_HELMET => $this->apiService->getBlackMarketItem(ItemEntity::ITEM_MAGE_HELMET),
            ItemEntity::ITEM_MAGE_ARMOR => $this->apiService->getBlackMarketItem(ItemEntity::ITEM_MAGE_ARMOR),
            ItemEntity::ITEM_MAGE_BOOTS => $this->apiService->getBlackMarketItem(ItemEntity::ITEM_MAGE_BOOTS),
            ItemEntity::ITEM_MAGE_FIRE_STAFF => $this->apiService->getBlackMarketItem(ItemEntity::ITEM_MAGE_FIRE_STAFF),
            ItemEntity::ITEM_MAGE_HOLY_STAFF => $this->apiService->getBlackMarketItem(ItemEntity::ITEM_MAGE_HOLY_STAFF),
            ItemEntity::ITEM_MAGE_ARCANE_STAFF => $this->apiService->getBlackMarketItem(
                ItemEntity::ITEM_MAGE_ARCANE_STAFF
            ),
            ItemEntity::ITEM_MAGE_FROST_STAFF => $this->apiService->getBlackMarketItem(
                ItemEntity::ITEM_MAGE_FROST_STAFF
            ),
            ItemEntity::ITEM_MAGE_CURSE_STAFF => $this->apiService->getBlackMarketItem(
                ItemEntity::ITEM_MAGE_CURSE_STAFF
            ),
            ItemEntity::ITEM_MAGE_TOME_STAFF => $this->apiService->getBlackMarketItem(ItemEntity::ITEM_MAGE_TOME_STAFF),
        ];
    }

    public function getHunterItems(): array
    {
        return [
            ItemEntity::ITEM_HUNTER_HELMET => $this->apiService->getBlackMarketItem(ItemEntity::ITEM_HUNTER_HELMET),
            ItemEntity::ITEM_HUNTER_ARMOR => $this->apiService->getBlackMarketItem(ItemEntity::ITEM_HUNTER_ARMOR),
            ItemEntity::ITEM_HUNTER_BOOTS => $this->apiService->getBlackMarketItem(ItemEntity::ITEM_HUNTER_BOOTS),
            ItemEntity::ITEM_HUNTER_BOW => $this->apiService->getBlackMarketItem(ItemEntity::ITEM_HUNTER_BOW),
            ItemEntity::ITEM_HUNTER_SPEAR => $this->apiService->getBlackMarketItem(ItemEntity::ITEM_HUNTER_SPEAR),
            ItemEntity::ITEM_HUNTER_NATURE_STAFF => $this->apiService->getBlackMarketItem(
                ItemEntity::ITEM_HUNTER_NATURE_STAFF
            ),
            ItemEntity::ITEM_HUNTER_DAGGER => $this->apiService->getBlackMarketItem(ItemEntity::ITEM_HUNTER_DAGGER),
            ItemEntity::ITEM_HUNTER_QUARTERSTAFF => $this->apiService->getBlackMarketItem(
                ItemEntity::ITEM_HUNTER_QUARTERSTAFF
            ),
            ItemEntity::ITEM_HUNTER_TORCH => $this->apiService->getBlackMarketItem(ItemEntity::ITEM_HUNTER_TORCH),
        ];
    }
}
