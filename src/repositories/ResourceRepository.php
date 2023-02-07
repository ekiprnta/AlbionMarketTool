<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use Doctrine\ORM\EntityManager;
use MZierdt\Albion\Entity\ResourceEntity;

class ResourceRepository
{
    public function __construct(
        private readonly EntityManager $entityManager
    ) {
    }

    public function update(ResourceEntity $resourceEntity): void
    {
        $this->entityManager->persist($resourceEntity);
        $this->entityManager->flush($resourceEntity);
    }

    public function findBy(array $params, array $sort = []): ?array
    {
        return $this->entityManager->getRepository(ResourceEntity::class)->findBy($params, $sort);
    }

    public function delete(ResourceEntity $resourceEntity): void
    {
        $this->entityManager->remove($resourceEntity);
        $this->entityManager->flush($resourceEntity);
    }

    public function createOrUpdate(ResourceEntity $resourceEntity): void
    {
        $oldResourceEntity = $this->entityManager->getRepository(ResourceEntity::class)->findOneBy(
            [
                'tier' => $resourceEntity->getTier(),
                'name' => $resourceEntity->getName(),
                'city' => $resourceEntity->getCity()
            ]
        );
        if ($oldResourceEntity !== null) {
            $oldResourceEntity->setSellOrderPrice($resourceEntity->getSellOrderPrice());
            $oldResourceEntity->setBuyOrderAge($resourceEntity->getBuyOrderPrice());
            $oldResourceEntity->setSellOrderAge($resourceEntity->getSellOrderAge());
            $oldResourceEntity->setBuyOrderAge($resourceEntity->getBuyOrderAge());
            $this->update($oldResourceEntity);
        } else {
            $this->update($resourceEntity);
        }
    }

    public function getRawResourcesByBonusCity(string $city): array
    {
        return $this->findBy(['bonusCity' => $city, 'raw' => true]);
    }

    public function getRawResourcesByCity(string $city): array
    {
        return $this->findBy(['city' => $city, 'raw' => true]);
    }

    public function getResourcesByCity(string $city): array
    {
        return $this->findBy(['city' => $city, 'raw' => false]) ?? [];
    }

    public function getResourcesByBonusCity(string $city): array
    {
        return $this->findBy(['bonusCity' => $city, 'raw' => false]) ?? [];
    }
}
