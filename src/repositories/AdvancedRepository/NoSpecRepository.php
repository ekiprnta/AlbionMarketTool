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
        ]);

        if ($oldNoSpeEntity !== null) {
            $oldNoSpeEntity->setMaterialCostSell($noSpecEntity->getMaterialCostSell());
            $oldNoSpeEntity->setProfitSell($noSpecEntity->getProfitSell());
            $oldNoSpeEntity->setProfitPercentageSell($noSpecEntity->getProfitPercentageSell());
            $oldNoSpeEntity->setProfitGradeSell($noSpecEntity->getProfitGradeSell());

            $oldNoSpeEntity->setMaterialCostBuy($noSpecEntity->getMaterialCostBuy());
            $oldNoSpeEntity->setProfitBuy($noSpecEntity->getProfitBuy());
            $oldNoSpeEntity->setProfitPercentageBuy($noSpecEntity->getProfitPercentageBuy());
            $oldNoSpeEntity->setProfitGradeBuy($noSpecEntity->getProfitGradeBuy());

            $oldNoSpeEntity->setComplete($noSpecEntity->isComplete());

            $this->update($oldNoSpeEntity);
        } else {
            $this->update($noSpecEntity);
        }
    }
}