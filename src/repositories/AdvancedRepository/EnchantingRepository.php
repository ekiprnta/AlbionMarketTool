<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories\AdvancedRepository;

use MZierdt\Albion\Entity\AdvancedEntities\EnchantingEntity;
use MZierdt\Albion\repositories\Repository;

class EnchantingRepository extends Repository
{
    public function getAllEnchantingByCity(string $city)
    {
        return $this->findBy(EnchantingEntity::class, [
            'complete' => true,
            'city' => $city,
        ]);
    }

    public function createOrUpdate(EnchantingEntity $enchantingEntity): void
    {
        $oldEnchantingEntity = $this->entityManager->getRepository(EnchantingEntity::class)->findOneBy(
            [
                'city' => $enchantingEntity->getCity(),
                'baseItem' => $enchantingEntity->getBaseItem(),
            ]
        );

        if ($oldEnchantingEntity !== null) {
            $oldEnchantingEntity = $this->updateClass($enchantingEntity, $oldEnchantingEntity);
            $this->update($oldEnchantingEntity);
        } else {
            $this->update($enchantingEntity);
        }
    }
}
