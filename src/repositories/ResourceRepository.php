<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use MZierdt\Albion\Entity\ResourceEntity;

class ResourceRepository extends Repository
{
    public function createOrUpdate(ResourceEntity $resourceEntity): void
    {
        $oldResourceEntity = $this->entityManager->getRepository(ResourceEntity::class)->findOneBy(
            [
                'tier' => $resourceEntity->getTier(),
                'name' => $resourceEntity->getName(),
                'city' => $resourceEntity->getCity(),
                'raw' => $resourceEntity->isRaw(),
            ]
        );

        if ($oldResourceEntity !== null) {
            if ($resourceEntity->getSellOrderPrice() !== 0) {
                $oldResourceEntity->setSellOrderPrice($resourceEntity->getSellOrderPrice());
                $oldResourceEntity->setSellOrderDate($resourceEntity->getSellOrderDate());
            }
            if ($resourceEntity->getBuyOrderPrice() !== 0) {
                $oldResourceEntity->setBuyOrderPrice($resourceEntity->getBuyOrderPrice());
                $oldResourceEntity->setBuyOrderDate($resourceEntity->getBuyOrderDate());
            }
            $this->update($oldResourceEntity);
        } else {
            $this->update($resourceEntity);
        }
    }

    public function getRawResourcesByBonusCity(string $city): array
    {
        return $this->findBy(ResourceEntity::class, [
            'bonusCity' => $city,
            'raw' => true,
            'city' => $city,
        ]) ?? [];
    }

    public function getRawResourcesByCity(string $city): array
    {
        return $this->findBy(ResourceEntity::class, [
            'city' => $city,
            'raw' => true,
        ]) ?? [];
    }

    public function getResourcesByCity(string $city): array
    {
        return $this->findBy(ResourceEntity::class, [
            'city' => $city,
            'raw' => false,
        ]) ?? [];
    }

    public function getResourcesByBonusCity(string $city): array
    {
        return $this->findBy(ResourceEntity::class, [
            'bonusCity' => $city,
            'raw' => false,
            'city' => $city,
        ]) ?? [];
    }

    public function getBonusResources()
    {
        return $this->entityManager->getRepository(ResourceEntity::class)
            ->createQueryBuilder('r')
            ->where('r.city = r.bonusCity')
            ->andWhere('r.raw = false')
            ->getQuery()
            ->getResult();
    }
}
