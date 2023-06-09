<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories\AdvancedRepository;

use MZierdt\Albion\Entity\AdvancedEntities\TransmutationEntity;
use MZierdt\Albion\repositories\Repository;

class TransmutationRepository extends Repository
{
    public function getAllTransmutationByCity(string $city): array
    {
        return $this->findBy(TransmutationEntity::class, [
            'complete' => true,
            'city' => $city,
        ]);
    }

    public function createOrUpdate(TransmutationEntity $transmutationEntity): void
    {
        $oldTransmutationEntity = $this->entityManager->getRepository(TransmutationEntity::class)->findOneBy([
            'city' => $transmutationEntity->getCity(),
            'resourceType' => $transmutationEntity->getResourceType(),
            'pathName' => $transmutationEntity->getPathName(),
        ]);

        if ($oldTransmutationEntity !== null) {
            $oldTransmutationEntity = $this->updateClass($transmutationEntity, $oldTransmutationEntity);
            $this->update($oldTransmutationEntity);
        } else {
            $this->update($transmutationEntity);
        }
    }
}
