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
            $oldItemEntity->setSellOrderPrice($itemEntity->getSellOrderPrice());
            $oldItemEntity->setBuyOrderAge($itemEntity->getBuyOrderPrice());
            $oldItemEntity->setSellOrderAge($itemEntity->getSellOrderAge());
            $oldItemEntity->setBuyOrderAge($itemEntity->getBuyOrderAge());
            $this->update($oldItemEntity);
        } else {
            $this->update($itemEntity);
        }
    }

    public function getItemsByLocation(string $city): array
    {
        return $this->findBy(ItemEntity::class, ['city' => $city]) ?? [];
    }

    public function getBlackMarketItemsFromCity(string $city): array
    {
        return $this->findBy(ItemEntity::class, ['bonusCity' => $city, 'city' => 'Black Market']) ?? [];
    }
}
