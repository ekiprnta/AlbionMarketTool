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

    public function getEnchantingForCity(string $city): array
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
            $itemEntity = $enchantingEntity->getItemEntity();

            $enchantingEntity->setBaseEnchantment($this->enchantingHelper->getEnchantment($itemEntity->getTier()));

            $enchantingEntity->setHigherEnchantmentItem(
                $this->enchantingHelper->calculateHigherEnchantmentItem(
                    $itemEntity->getTier(),
                    $itemEntity->getName(),
                    $items
                )
            );

            $enchantingEntity->setEnchantmentMaterial(
                $this->enchantingHelper->calculateEnchantmentMaterial($itemEntity->getTier(), $materials)
            );

            $enchantingEntity->setMaterialAmount(
                $this->enchantingHelper->calculateMaterialAmount($itemEntity->getTotalResourceAmount())
            );

            $enchantingEntity->setMaterialCost(
                $this->enchantingHelper->calculateMaterialCost(
                    $enchantingEntity->getMaterialAmount(),
                    $enchantingEntity->getEnchantmentMaterial()
                        ->getBuyOrderPrice()
                )
            );

            $enchantingEntity->setProfit(
                $this->enchantingHelper->calculateProfit(
                    $itemEntity->getSellOrderPrice(),
                    $enchantingEntity->getHigherEnchantmentItem()
                        ->getSellOrderPrice(),
                    $enchantingEntity->getMaterialCost()
                )
            );

            $enchantingEntity->setProfitQuotient(
                $this->enchantingHelper->calculateProfitQuotient($enchantingEntity->getProfit(), 1)
            );

            $enchantingEntity->setProfitGrade(
                $this->enchantingHelper->calculateProfitGrade($enchantingEntity->getProfitQuotient())
            );
//            dump($enchantingEntity->getProfitQuotient());
        }

        return $enchantingEntities;
    }
}
