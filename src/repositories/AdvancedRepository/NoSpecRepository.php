<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories\AdvancedRepository;

use MZierdt\Albion\Entity\AdvancedEntities\NoSpecEntity;
use MZierdt\Albion\repositories\Repository;

class NoSpecRepository extends Repository
{
    public function getAllNoSpecCraftingByCity(string $city): array
    {
        return $this->findBy(NoSpecEntity::class, [
            'complete' => true,
            'city' => $city,
        ]);
    }

    public function createOrUpdate(NoSpecEntity $noSpecEntity): void
    {
        $oldNoSpeEntity = $this->entityManager->getRepository(NoSpecEntity::class)->findOneBy([
            'city' => $noSpecEntity->getCity(),
            'specialItem' => $noSpecEntity->getSpecialItem(),
        ]);

        if ($oldNoSpeEntity !== null) {
            $oldNoSpeEntity = $this->updateClass($noSpecEntity, $oldNoSpeEntity);
            $this->update($oldNoSpeEntity);
        } else {
            $this->update($noSpecEntity);
        }
    }
}
