<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories\AdvancedRepository;

use MZierdt\Albion\Entity\AdvancedEntities\RefiningEntity;
use MZierdt\Albion\repositories\Repository;

class RefiningRepository extends Repository
{
    public function getAllRefiningByCity(string $city): array
    {
        if ($city === 'Bridgewatch') {
            throw new \InvalidArgumentException('Bridgewatch is currently not Supported');
        }

        return $this->findBy(RefiningEntity::class, ['complete' => true, 'city' => $city]);
    }

    public function createOrUpdate(RefiningEntity $refiningEntity): void
    {
        $oldRefiningEntity = $this->entityManager->getRepository(RefiningEntity::class)->findOneBy(
            [
                'city' => $refiningEntity->getCity(),
                'refinedResource' => $refiningEntity->getRefinedResource(),
            ]
        );

        if ($oldRefiningEntity !== null) {
            $oldRefiningEntity->setMaterialCostSell($refiningEntity->getMaterialCostSell());
            $oldRefiningEntity->setProfitSell($refiningEntity->getProfitSell());
            $oldRefiningEntity->setProfitPercentageSell($refiningEntity->getProfitPercentageSell());
            $oldRefiningEntity->setProfitGradeSell($refiningEntity->getProfitGradeSell());

            $oldRefiningEntity->setMaterialCostSell($refiningEntity->getMaterialCostSell());
            $oldRefiningEntity->setProfitSell($refiningEntity->getProfitSell());
            $oldRefiningEntity->setProfitPercentageSell($refiningEntity->getProfitPercentageSell());
            $oldRefiningEntity->setProfitGradeSell($refiningEntity->getProfitGradeSell());

            $refiningEntity->setComplete($refiningEntity->isComplete());

            $this->update($oldRefiningEntity);
        } else {
            $this->update($refiningEntity);
        }
    }
}