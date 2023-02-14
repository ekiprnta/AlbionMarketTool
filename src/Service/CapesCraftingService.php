<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

use MZierdt\Albion\Entity\NoSpecEntity;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\repositories\MaterialRepository;

class CapesCraftingService
{
    public function __construct(
        private readonly ItemRepository $itemRepository,
        private readonly MaterialRepository $materialRepository,
        private readonly CapesCraftingHelper $ccHelper
    ) {
    }

    public function getCapesByCity(string $city): array
    {
        $capes = $this->itemRepository->getArtifactCapesByCity($city);
        $defaultCapes = $this->itemRepository->getDefaultCapesByCity($city);

        $heartsAndSigils = $this->materialRepository->getHeartsAndSigilsByCity($city);
        $artifacts = $this->materialRepository->getCapeArtifactsByCity($city);

        $noSpecEntities = [];
        foreach ($capes as $cape) {
            $noSpecEntities[] = new NoSpecEntity($cape);
        }

        /** @var NoSpecEntity $noSpecEntity */
        foreach ($noSpecEntities as $noSpecEntity) {
            $specialCape = $noSpecEntity->getSpecialCape();
            $noSpecEntity->setDefaultCape(
                $this->ccHelper->calculateDefaultCape($specialCape->getTier(), $defaultCapes)
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
                    ->getSellOrderPrice();
            }

            $noSpecEntity->setMaterialCost(
                $this->ccHelper->calculateMaterialCost(
                    $noSpecEntity->getDefaultCape()
                        ->getSellOrderPrice(),
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
