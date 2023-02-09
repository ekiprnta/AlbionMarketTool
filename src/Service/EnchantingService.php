<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

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
}