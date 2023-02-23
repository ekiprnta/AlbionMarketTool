<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories\AdvancedRepository;

use MZierdt\Albion\Entity\AdvancedEntities\ListDataEntity;
use MZierdt\Albion\repositories\Repository;

class ListDataRepository extends Repository
{
    public function getAllRefiningByType(string $type): array
    {
        return $this->findBy(ListDataEntity::class, [
            'type' => $type,
        ]);
    }

    public function createOrUpdate(ListDataEntity $listDataEntity): void
    {
        $oldListDataEntity = $this->entityManager->getRepository(ListDataEntity::class)->findOneBy(
            [
                'fortsterlingObject' => $listDataEntity->getFortsterlingObject(),
            ]
        );

        if ($oldListDataEntity !== null) {
            $oldListDataEntity->setCheapestObjectCitySellOrder($listDataEntity->getCheapestObjectCitySellOrder());
            $oldListDataEntity->setMostExpensiveObjectCitySellOrder(
                $listDataEntity->getMostExpensiveObjectCitySellOrder()
            );
            $oldListDataEntity->setCheapestObjectCityBuyOrder($listDataEntity->getCheapestObjectCityBuyOrder());
            $oldListDataEntity->setMostExpensiveObjectCityBuyOrder(
                $listDataEntity->getMostExpensiveObjectCityBuyOrder()
            );

            $this->update($oldListDataEntity);
        } else {
            $this->update($listDataEntity);
        }
    }
}
