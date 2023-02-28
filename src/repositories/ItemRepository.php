<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use MZierdt\Albion\Entity\AlbionItemEntity;
use MZierdt\Albion\Entity\ItemEntity;

class ItemRepository extends Repository
{
    public function createOrUpdate(ItemEntity|AlbionItemEntity $itemEntity): void
    {
        $oldItemEntity = $this->entityManager->getRepository(ItemEntity::class)->findOneBy(
            [
                'tier' => $itemEntity->getTier(),
                'name' => $itemEntity->getName(),
                'city' => $itemEntity->getCity(),
            ]
        );
        if ($oldItemEntity !== null) {
            if ($itemEntity->getSellOrderPrice() !== 0) {
                $oldItemEntity->setSellOrderPrice($itemEntity->getSellOrderPrice());
                $oldItemEntity->setSellOrderDate($itemEntity->getSellOrderDate());
            }
            if ($itemEntity->getBuyOrderPrice() !== 0) {
                $oldItemEntity->setBuyOrderPrice($itemEntity->getBuyOrderPrice());
                $oldItemEntity->setBuyOrderDate($itemEntity->getBuyOrderDate());
            }
            $this->update($oldItemEntity);
        } else {
            $this->update($itemEntity);
        }
    }

    public function getItemsByLocationForBM(string $city): array
    {
        return $this->findBy(ItemEntity::class, [
            'city' => $city,
            'blackMarketSellable' => true,
        ]) ?? [];
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
        return $this->findBy(ItemEntity::class, ['city' => $city, 'name' => 'cape']) ?? [];
    }

    public function getRoyalItemsByCity(string $city): array
    {
        return $this->findBy(ItemEntity::class, ['city' => $city, 'weaponGroup' => 'royal']) ?? [];
    }

    public function getDefaultArmor(string $city)
    {
        return $this->entityManager->getRepository(ItemEntity::class)->createQueryBuilder('i')
            ->where('i.city = :city')
            ->andWhere('i.name Like :name')
            ->andWhere('i.bonusCity Not Like :bonusCity')
            ->setParameter('city', $city)
            ->setParameter('name', '%set%')
            ->setParameter('bonusCity', 'Caerleon')
            ->getQuery()
            ->getResult() ?? [];
    }
}
