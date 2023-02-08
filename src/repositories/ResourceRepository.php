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
            ]
        );
        if ($oldResourceEntity !== null) {
            if ($resourceEntity->getSellOrderPrice() !== 0) {
                $oldResourceEntity->setSellOrderPrice($resourceEntity->getSellOrderPrice());
                $oldResourceEntity->setBuyOrderAge($resourceEntity->getBuyOrderPrice());
            }
            if ($resourceEntity->getBuyOrderPrice() !== 0) {
                $oldResourceEntity->setSellOrderAge($resourceEntity->getSellOrderAge());
                $oldResourceEntity->setBuyOrderAge($resourceEntity->getBuyOrderAge());
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
        ]);
    }

    public function getRawResourcesByCity(string $city): array
    {
        return $this->findBy(ResourceEntity::class, [
            'city' => $city,
            'raw' => true,
        ]);
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
        return $this->findBy(ResourceEntity::class, ['bonusCity' => $city, 'raw' => false]) ?? [];
    }
}
