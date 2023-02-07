<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use Doctrine\ORM\EntityManager;
use MZierdt\Albion\Entity\ItemEntity;

class ItemRepository
{
    public function __construct(
        private readonly EntityManager $entityManager
    ) {
    }

    public function update(ItemEntity $resourceEntity): void
    {
        $this->entityManager->persist($resourceEntity);
        $this->entityManager->flush($resourceEntity);
    }

    public function findBy(array $params, array $sort = []): ?array
    {
        return $this->entityManager->getRepository(ItemEntity::class)->findBy($params, $sort);
    }

    public function delete(ItemEntity $resourceEntity): void
    {
        $this->entityManager->remove($resourceEntity);
        $this->entityManager->flush($resourceEntity);
    }

    public function getItemsByLocation(string $city): array
    {
        return $this->findBy(['city' => $city]) ?? [];
//        $statement = $this->pdoConnection->prepare(
//            <<<SQL
//            SELECT *
//            FROM albion_db.items
//            WHERE albion_db.items.city = :city
//SQL
//        );
//        $statement->bindParam(':city', $city);
//        $statement->execute();
//
//        $itemsArray = [];
//        foreach ($statement->getIterator() as $itemInformation) {
//            $itemsArray[] = new ItemEntity($itemInformation);
//        }
//        return $itemsArray;
    }

    public function createOrUpdate(ItemEntity $itemEntity): void
    {
        $oldItemEntity = $this->entityManager->getRepository(ItemEntity::class)->findOneBy(
            [
                'weaponGroup' => $itemEntity->getWeaponGroup(),
                'tier' => $itemEntity->getTier(),
                'name' => $itemEntity->getName(),
                'city' => $itemEntity->getCity()
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

    public function getBlackMarketItemsFromCity(string $city): array
    {
        return $this->findBy(['bonusCity' => $city, 'city' => 'Black Market']) ?? [];
    }
}
