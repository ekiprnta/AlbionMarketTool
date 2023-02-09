<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

use MZierdt\Albion\Entity\EnchantingEntity;
use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\repositories\MaterialRepository;

class EnchantingService
{
    public function __construct(
        private readonly MaterialRepository $materialRepository,
        private readonly ItemRepository $itemRepository,
        private readonly EnchantingHelper $enchantingHelper,
    ) {
    }

    public function getDataForCity(string $city): array
    {
        $items = $this->itemRepository->getItemsByLocation($city);
        $materials = $this->materialRepository->getMaterialsByLocation($city);

        $enchantingEntities = [];
        /** @var ItemEntity $item */
        foreach ($items as $item) {
            if (!($item->getTier() === 30 || $item->getTier() === 20) && $this->enchantingHelper->getEnchantment(
                    $item->getTier()
                ) < 3) {
                $enchantingEntities[] = new EnchantingEntity($item);
            }
        }

        /** @var EnchantingEntity $enchantingEntity */
        foreach ($enchantingEntities as $enchantingEntity) {
            $enchantingEntity->setBaseEnchantment(
                $this->enchantingHelper->getEnchantment($enchantingEntity->getItemEntity()->getTier())
            );

            $enchantingEntity->setHigherEnchantmentItem(
                $this->enchantingHelper->calculateHigherEnchantmentItem(
                    $enchantingEntity->getItemEntity()->getTier(),
                    $enchantingEntity->getItemEntity()->getName(),
                    $items
                )
            );

            $enchantingEntity->setEnchantmentMaterial(
                $this->enchantingHelper->calculateEnchantmentMaterial(
                    $enchantingEntity->getItemEntity()->getTier(),
                    $materials
                )
            );

            $enchantingEntity->setMaterialAmount(
                $this->enchantingHelper->calculateMaterialAmount(
                    $enchantingEntity->getItemEntity()->getTotalResourceAmount()
                )
            );

            $enchantingEntity->setMaterialCost(
                $this->enchantingHelper->calculateMaterialCost(
                    $enchantingEntity->getMaterialAmount(),
                    $enchantingEntity->getEnchantmentMaterial()->getBuyOrderPrice()
                )
            );

            $enchantingEntity->setProfit(
                $this->enchantingHelper->calculateProfit(
                    $enchantingEntity->getItemEntity()->getSellOrderPrice(),
                    $enchantingEntity->getHigherEnchantmentItem()->getSellOrderPrice(),
                    $enchantingEntity->getMaterialCost()
                )
            );

            $enchantingEntity->setProfitQuotient(
                $this->enchantingHelper->calculateProfitQuotient($enchantingEntity->getProfit(), 1)
            );

            $enchantingEntity->setProfitGrade(
                $this->enchantingHelper->calculateProfitGrade($enchantingEntity->getProfitQuotient())
            );
        }

        return $enchantingEntities;
    }
}