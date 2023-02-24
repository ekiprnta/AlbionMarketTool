<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories\AdvancedRepository;

use MZierdt\Albion\Entity\AdvancedEntities\BlackMarketCraftingEntity;
use MZierdt\Albion\repositories\Repository;

class BlackMarketCraftingRepository extends Repository
{
    public function getAllBmCraftingByCity(string $city): array
    {
        return $this->findBy(BlackMarketCraftingEntity::class, [
            'complete' => true,
            'city' => $city,
        ]);
    }

    public function createOrUpdate(BlackMarketCraftingEntity $blackMarketCraftingEntity): void
    {
        $oldBlackMarketCraftingEntity = $this->entityManager->getRepository(
            BlackMarketCraftingEntity::class
        )->findOneBy(
            [
                'city' => $blackMarketCraftingEntity->getCity(),
                'item' => $blackMarketCraftingEntity->getItem(),
            ]
        );

        if ($oldBlackMarketCraftingEntity !== null) {
            $oldBlackMarketCraftingEntity = $this->updateClass(
                $blackMarketCraftingEntity,
                $oldBlackMarketCraftingEntity
            );
            $this->update($oldBlackMarketCraftingEntity);
        } else {
            $this->update($blackMarketCraftingEntity);
        }
    }
}
