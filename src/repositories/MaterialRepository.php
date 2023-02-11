<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use MZierdt\Albion\Entity\MaterialEntity;

class MaterialRepository extends Repository
{
    public function createOrUpdate(MaterialEntity $materialEntity)
    {
        $oldMaterialEntity = $this->entityManager->getRepository(MaterialEntity::class)
            ->findOneBy(
                [
                    'tier' => $materialEntity->getTier(),
                    'name' => $materialEntity->getName(),
                    'city' => $materialEntity->getCity(),
                ]
            );
        if ($oldMaterialEntity !== null) {
            if ($materialEntity->getSellOrderPrice() !== 0) {
                $oldMaterialEntity->setSellOrderPrice($materialEntity->getSellOrderPrice());
                $oldMaterialEntity->setBuyOrderPrice($materialEntity->getBuyOrderPrice());
            }
            if ($materialEntity->getBuyOrderPrice() !== 0) {
                $oldMaterialEntity->setSellOrderAge($materialEntity->getSellOrderAge());
                $oldMaterialEntity->setBuyOrderAge($materialEntity->getBuyOrderAge());
            }
            $this->update($oldMaterialEntity);
        } else {
            $this->update($materialEntity);
        }
    }

    public function getMaterialsByLocation(string $city): ?array
    {
        return $this->findBy(MaterialEntity::class, ['city' => $city]);
    }
}
