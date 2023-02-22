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
        $oldRefiningEntity = $this->entityManager->getRepository(BlackMarketCraftingEntity::class)->findOneBy(
            [
                'city' => $blackMarketCraftingEntity->getCity(),
                'item' => $blackMarketCraftingEntity->getItem(),
            ]
        );

        if ($oldRefiningEntity !== null) {
            $oldRefiningEntity->setMaterialCostSell($blackMarketCraftingEntity->getMaterialCostSell());
            $oldRefiningEntity->setProfitSell($blackMarketCraftingEntity->getProfitSell());
            $oldRefiningEntity->setProfitPercentageSell($blackMarketCraftingEntity->getProfitPercentageSell());
            $oldRefiningEntity->setProfitGradeSell($blackMarketCraftingEntity->getProfitGradeSell());

            $oldRefiningEntity->setMaterialCostSell($blackMarketCraftingEntity->getMaterialCostSell());
            $oldRefiningEntity->setProfitSell($blackMarketCraftingEntity->getProfitSell());
            $oldRefiningEntity->setProfitPercentageSell($blackMarketCraftingEntity->getProfitPercentageSell());
            $oldRefiningEntity->setProfitGradeSell($blackMarketCraftingEntity->getProfitGradeSell());

            $blackMarketCraftingEntity->setComplete($blackMarketCraftingEntity->isComplete());

            $this->update($oldRefiningEntity);
        } else {
            $this->update($blackMarketCraftingEntity);
        }
    }
}