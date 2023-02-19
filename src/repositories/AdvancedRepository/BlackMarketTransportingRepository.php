<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories\AdvancedRepository;

use MZierdt\Albion\Entity\AdvancedEntities\BlackMarketTransportEntity;
use MZierdt\Albion\repositories\Repository;

class BlackMarketTransportingRepository extends Repository
{
    public function getAllTransportingByCity(string $city): void
    {
        $this->findBy(BlackMarketTransportEntity::class, ['complete' => true, 'city' => $city]);
    }

    public function createOrUpdate(BlackMarketTransportEntity $bmtEntity)
    {
        $oldBmtEntity = $this->entityManager->getRepository(BlackMarketTransportEntity::class)->findOneBy(
            [
                'cityItem' => $bmtEntity->getCityItem(),
                'bmItem' => $bmtEntity->getBmItem(),
            ]
        );

        if ($oldBmtEntity !== null) {
            $oldBmtEntity->setMaterialCostSell($bmtEntity->getMaterialCostSell());
            $oldBmtEntity->setProfitSell($bmtEntity->getProfitSell());
            $oldBmtEntity->setProfitPercentageSell($bmtEntity->getProfitPercentageSell());
            $oldBmtEntity->setProfitGradeSell($bmtEntity->getProfitGradeSell());

            $oldBmtEntity->setMaterialCostBuy($bmtEntity->getMaterialCostBuy());
            $oldBmtEntity->setProfitBuy($bmtEntity->getProfitBuy());
            $oldBmtEntity->setProfitPercentageBuy($bmtEntity->getProfitPercentageBuy());
            $oldBmtEntity->setProfitGradeBuy($bmtEntity->getProfitGradeBuy());

            $oldBmtEntity->setComplete($bmtEntity->isComplete());

            $this->update($oldBmtEntity);
        } else {
            $this->update($bmtEntity);
        }
    }
}