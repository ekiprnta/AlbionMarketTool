<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories\AdvancedRepository;

use MZierdt\Albion\Entity\AdvancedEntities\RefiningEntity;
use MZierdt\Albion\repositories\Repository;

class RefiningRepository extends Repository
{
    public function getAllRefiningByCity(string $city): array
    {
        return $this->findBy(RefiningEntity::class, ['complete' => true, 'city' => $city]);
    }

    public function createOrUpdate(RefiningEntity $refiningEntity): void
    {
        $oldRefiningEntity = $this->entityManager->getRepository(RefiningEntity::class)->findOneBy(
            [
                'city' => $refiningEntity->getRefinedResource()->getCity(),
            ]
        );

        if ($oldRefiningEntity !== null) {
            $this->update($oldRefiningEntity);
        } else {
            $this->update($refiningEntity);
        }
    }
}