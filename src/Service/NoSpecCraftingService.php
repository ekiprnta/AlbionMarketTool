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
            $specialCape = $noSpecEntity->getSpecialItem();
            $noSpecEntity->setDefaultItem(
                $this->ccHelper->calculateDefaultItem($specialCape->getTier(), $defaultItems)
            );

            $noSpecEntity->setSecondResource(
                $this->ccHelper->calculateSecondResource(
                    $specialCape
                        ->getSecondaryResource(),
                    $specialCape
                        ->getTier(),
                    $heartsAndSigils
                )
            );
            $noSpecEntity->setArtifact(
                $this->ccHelper->calculateArtifact(
                    $specialCape
                        ->getArtifact(),
                    $specialCape
                        ->getTier(),
                    $artifacts
                )
            );
            if ($noSpecEntity->getArtifact() === null) {
                $artifactPrice = 0;
            } else {
                $artifactPrice = $noSpecEntity->getArtifact()
                    ->getBuyOrderPrice();
            }

            $noSpecEntity->setMaterialCost(
                $this->ccHelper->calculateMaterialCost(
                    $noSpecEntity->getDefaultItem()
                        ->getBuyOrderPrice(),
                    $noSpecEntity->getSecondResource()
                        ->getSellOrderPrice(),
                    $specialCape
                        ->getSecondaryResourceAmount(),
                    $artifactPrice
                )
            );
            $noSpecEntity->setProfit(
                $this->ccHelper->calculateProfit(
                    $specialCape
                        ->getSellOrderPrice(),
                    $noSpecEntity->getMaterialCost()
                )
            );

            $noSpecEntity->setProfitQuotient($this->ccHelper->calculateProfitQuotient($noSpecEntity->getProfit(), 1));
            $noSpecEntity->setProfitGrade(($this->ccHelper->calculateProfitGrade($noSpecEntity->getProfitQuotient())));
        }

        return $noSpecEntities;
    }
}
