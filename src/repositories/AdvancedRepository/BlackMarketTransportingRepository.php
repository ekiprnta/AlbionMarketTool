<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories\AdvancedRepository;

use MZierdt\Albion\Entity\AdvancedEntities\BlackMarketTransportEntity;
use MZierdt\Albion\repositories\Repository;

class BlackMarketTransportingRepository extends Repository
{
    public function getAllTransportingByCity(string $city): array
    {
        return $this->findBy(BlackMarketTransportEntity::class, [
            'complete' => true,
            'city' => $city,
        ]);
    }

    public function createOrUpdate(BlackMarketTransportEntity $bmtEntity): void
    {
        $oldBmtEntity = $this->entityManager->getRepository(BlackMarketTransportEntity::class)->findOneBy(
            [
                'cityItem' => $bmtEntity->getCityItem(),
                'bmItem' => $bmtEntity->getBmItem(),
            ]
        );

        if ($oldBmtEntity !== null) {
            $oldBmtEntity = $this->updateClass($bmtEntity, $oldBmtEntity);
            $this->update($oldBmtEntity);
        } else {
            $this->update($bmtEntity);
        }
    }
}
