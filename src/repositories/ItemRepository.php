<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use MZierdt\Albion\Entity\ItemEntity;

class ItemRepository extends Repository
{
    public function createOrUpdate(ItemEntity $itemEntity): void
    {
        $oldItemEntity = $this->entityManager->getRepository(ItemEntity::class)->findOneBy(
            [
                'weaponGroup' => $itemEntity->getWeaponGroup(),
                'tier' => $itemEntity->getTier(),
                'name' => $itemEntity->getName(),
                'city' => $itemEntity->getCity(),
            ]
        );
        if ($oldItemEntity !== null) {
            if ($itemEntity->getSellOrderPrice() !== 0) {
                $oldItemEntity->setSellOrderPrice($itemEntity->getSellOrderPrice());
                $oldItemEntity->setBuyOrderAge($itemEntity->getBuyOrderPrice());
            }
            if ($itemEntity->getBuyOrderPrice() !== 0) {
                $oldItemEntity->setSellOrderAge($itemEntity->getSellOrderAge());
                $oldItemEntity->setBuyOrderAge($itemEntity->getBuyOrderAge());
            }
            $this->update($oldItemEntity);
        } else {
            $this->update($itemEntity);
        }
    }

    public function getItemsByLocationForBM(string $city): array
    {
        return $this->findBy(ItemEntity::class, ['city' => $city, 'blackMarketSellable' => true]) ?? [];
    }

    public function getItemsByLocation(string $city): array
    {
        return $this->findBy(ItemEntity::class, ['city' => $city]) ?? [];
    }

    public function getBlackMarketItemsFromCity(string $city): array
    {
        return $this->findBy(
            ItemEntity::class,
            ['bonusCity' => $city, 'city' => 'Black Market', 'blackMarketSellable' => true]
        ) ?? [];
    }

    public function getArtifactCapesByCity(string $city): array
    {
        return $this->findBy(
            ItemEntity::class,
            ['city' => $city, 'weaponGroup' => 'accessories', 'blackMarketSellable' => false]
        ) ?? [];
    }

    public function getDefaultCapesByCity(string $city): array
    {
        return $this->findBy(
            ItemEntity::class,
            ['city' => $city, 'name' => 'cape']
        ) ?? [];
    }
}
