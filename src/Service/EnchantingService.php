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
        $items = $this->filter($items);

        $bmItems = $this->itemRepository->getItemsByLocationForBM('Black Market');

        $materials = $this->materialRepository->getMaterialsByLocation($city);

        $enchantingEntities = [];
        /** @var ItemEntity $item */
        foreach ($items as $item) {
            if (! ($item->getTier() === 30 || $item->getTier() === 20) && $this->enchantingHelper->getEnchantment(
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
                    $bmItems
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

            $enchantingEntity->setProfitPercentage(
                $this->enchantingHelper->calculateProfitPercentage(
                    $enchantingEntity->getHigherEnchantmentItem()
                        ->getSellOrderPrice(),
                    $enchantingEntity->getMaterialCost() + $enchantingEntity->getItemEntity()
                        ->getSellOrderPrice()
                )
            );

            $enchantingEntity->setProfitGrade(
                $this->enchantingHelper->calculateProfitGrade($enchantingEntity->getProfitPercentage())
            );
//            dump($enchantingEntity->getProfitQuotient());
        }

        return $enchantingEntities;
    }

    private function filter(array $items): array // TOdo better filtering
    {
        /** @var ItemEntity $item */
        foreach ($items as $key => $item) {
            if ($item->getTotalResourceAmount() === 8) {
                $bla = 1;
            } elseif ($item->getTotalResourceAmount() === 16) {
                $bla = 1;
            } elseif ($item->getTotalResourceAmount() === 24) {
                $bla = 1;
            } elseif ($item->getTotalResourceAmount() === 32) {
                $bla = 1;
            } else {
                unset($items[$key]);
            }
        }
        return $items;
    }
}
