<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

use MZierdt\Albion\Entity\NoSpecEntity;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\repositories\MaterialRepository;

class NoSpecCraftingService
{
    public function __construct(
        private readonly ItemRepository $itemRepository,
        private readonly MaterialRepository $materialRepository,
        private readonly NoSpecCraftingHelper $ccHelper
    ) {
    }

    public function getCapesByCity(string $city): array
    {
        $capes = $this->itemRepository->getArtifactCapesByCity($city);
        $royalItems = $this->itemRepository->getRoyalItemsByCity($city);
        $capesAndRoyalItems = array_merge($capes, $royalItems);

        $defaultCapes = $this->itemRepository->getDefaultCapesByCity($city);
        $defaultArmor = $this->itemRepository->getDefaultArmor($city);
        $defaultItems = array_merge($defaultArmor, $defaultCapes);

        $heartsAndSigils = $this->materialRepository->getHeartsAndSigilsByCity($city);
        $artifacts = $this->materialRepository->getCapeArtifactsByCity($city);

        $noSpecEntities = [];
        foreach ($capesAndRoyalItems as $item) {
            $noSpecEntities[] = new NoSpecEntity($item);
        }

        /** @var NoSpecEntity $noSpecEntity */
        foreach ($noSpecEntities as $noSpecEntity) {
            $specialItem = $noSpecEntity->getSpecialItem();
            $noSpecEntity->setDefaultItem(
                $this->ccHelper->calculateDefaultItem(
                    $specialItem->getTier(),
                    $specialItem->getPrimaryResource(),
                    $defaultItems
                )
            );

            $noSpecEntity->setSecondResource(
                $this->ccHelper->calculateSecondResource(
                    $specialItem
                        ->getSecondaryResource(),
                    $specialItem
                        ->getTier(),
                    $heartsAndSigils
                )
            );
            $noSpecEntity->setArtifact(
                $this->ccHelper->calculateArtifact(
                    $specialItem
                        ->getArtifact(),
                    $specialItem
                        ->getTier(),
                    $artifacts
                )
            );
            if ($noSpecEntity->getArtifact() === null) {
                $artifactPrice = 1;
            } else {
                $artifactPrice = $noSpecEntity->getArtifact()
                    ->getSellOrderPrice();
            }

            $noSpecEntity->setMaterialCost(
                $this->ccHelper->calculateMaterialCost(
                    $noSpecEntity->getDefaultItem()
                        ->getSellOrderPrice(),
                    $noSpecEntity->getSecondResource()
                        ->getSellOrderPrice(),
                    $specialItem
                        ->getSecondaryResourceAmount(),
                    $artifactPrice
                )
            );
            if ($specialItem->getRealName() === 'Undead Cape') {
                dump([$noSpecEntity, $artifactPrice, $noSpecEntity->getArtifact()]);
            }

            $noSpecEntity->setProfit(
                $this->ccHelper->calculateProfit(
                    $specialItem
                        ->getSellOrderPrice(),
                    $noSpecEntity->getMaterialCost()
                )
            );

            $noSpecEntity->setProfitPercentage(
                $this->ccHelper->calculateProfitPercentage(
                    $noSpecEntity->getSpecialItem()
                        ->getSellOrderPrice(),
                    $noSpecEntity->getMaterialCost()
                )
            );
            $noSpecEntity->setProfitGrade(
                ($this->ccHelper->calculateProfitGrade($noSpecEntity->getProfitPercentage()))
            );
        }

        return $noSpecEntities;
    }
}
