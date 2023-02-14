<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

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

        dd($capes);
        $allCapes = [];

        return $allCapes;
    }

}