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
            $oldTransmutationEntity->setMaterialCostSell($transmutationEntity->getMaterialCostSell());
            $oldTransmutationEntity->setProfitSell($transmutationEntity->getProfitSell());
            $oldTransmutationEntity->setProfitPercentageSell($transmutationEntity->getProfitPercentageSell());
            $oldTransmutationEntity->setProfitGradeSell($transmutationEntity->getProfitGradeSell());

            $oldTransmutationEntity->setMaterialCostBuy($transmutationEntity->getMaterialCostBuy());
            $oldTransmutationEntity->setProfitBuy($transmutationEntity->getProfitBuy());
            $oldTransmutationEntity->setProfitPercentageBuy($transmutationEntity->getProfitPercentageBuy());
            $oldTransmutationEntity->setProfitGradeBuy($transmutationEntity->getProfitGradeBuy());

            $oldTransmutationEntity->setComplete($transmutationEntity->isComplete());

            $this->update($oldTransmutationEntity);
        } else {
            $this->update($transmutationEntity);
        }
    }
}
