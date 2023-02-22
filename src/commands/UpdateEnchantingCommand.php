<?php

declare(strict_types=1);

namespace MZierdt\Albion\commands;

use MZierdt\Albion\AlbionMarket\EnchantingHelper;
use MZierdt\Albion\Entity\AdvancedEntities\EnchantingEntity;
use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\repositories\AdvancedRepository\EnchantingRepository;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\repositories\MaterialRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'market:enchanting', description: 'Update Calculations for noSpec/enchanting')]
class UpdateEnchantingCommand extends Command
{
    public function __construct(
        private readonly EnchantingHelper $enchantingService,
        private readonly EnchantingRepository $enchantingRepository,
        private readonly ItemRepository $itemRepository,
        private readonly MaterialRepository $materialRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $bmItems = $this->itemRepository->getItemsByLocationForBM('Black Market');

        $city = 'Fort Sterling';
        $output->writeln('Updating Enchanting from ' . $city . '...');

        $items = $this->itemRepository->getItemsByLocation($city);
        $items = $this->enchantingService->filterItems($items);
        $materials = $this->materialRepository->getMaterialsByLocation($city);

        $enchantingEntities = [];
        /** @var ItemEntity $item */
        foreach ($items as $item) {
            if (!($item->getTier() === 30 || $item->getTier() === 20) &&
                $this->enchantingService->getEnchantment($item->getTier()) < 3) {
                $enchantingEntities[] = new EnchantingEntity($item);
            }
        }

        /** @var EnchantingEntity $enchantingEntity */
        foreach ($enchantingEntities as $enchantingEntity) {
            $baseItem = $enchantingEntity->getBaseItem();

            $enchantingEntity->setBaseEnchantment($this->enchantingService->getEnchantment($baseItem->getTier()));

            $enchantingEntity->setHigherEnchantmentItem(
                $this->enchantingService->calculateHigherEnchantmentItem(
                    $baseItem->getTier(),
                    $baseItem->getName(),
                    $bmItems
                )
            );

            $enchantingEntity->setEnchantmentMaterial(
                $this->enchantingService->calculateEnchantmentMaterial($baseItem->getTier(), $materials)
            );

            $enchantingEntity->setMaterialAmount(
                $this->enchantingService->calculateMaterialAmount($baseItem->getTotalResourceAmount())
            );

            $enchantingEntity->setMaterialCostBuy(
                $this->enchantingService->calculateTotalMaterialCost(
                    $enchantingEntity->getMaterialAmount(),
                    $enchantingEntity->getEnchantmentMaterial()->getBuyOrderPrice()
                )
            );
            $enchantingEntity->setProfitSell(
                $this->enchantingService->calculateProfit(
                    $enchantingEntity->getHigherEnchantmentItem()->getSellOrderPrice(),
                    $baseItem->getSellOrderPrice() + $enchantingEntity->getMaterialCostBuy()
                )
            );
            $enchantingEntity->setProfitPercentageSell(
                $this->enchantingService->calculateProfitPercentage(
                    $enchantingEntity->getHigherEnchantmentItem()->getSellOrderPrice(),
                    $baseItem->getSellOrderPrice() + $enchantingEntity->getMaterialCostBuy()
                )
            );
            $enchantingEntity->setProfitGradeSell(
                $this->enchantingService->calculateProfitGrade($enchantingEntity->getProfitPercentageSell())
            );

            $enchantingEntity->setProfitBuy(
                $this->enchantingService->calculateProfit(
                    $enchantingEntity->getHigherEnchantmentItem()->getSellOrderPrice(),
                    $baseItem->getBuyOrderPrice() + $enchantingEntity->getMaterialCostBuy()
                )
            );
            $enchantingEntity->setProfitPercentageBuy(
                $this->enchantingService->calculateProfitPercentage(
                    $enchantingEntity->getHigherEnchantmentItem()->getSellOrderPrice(),
                    $baseItem->getBuyOrderPrice() + $enchantingEntity->getMaterialCostBuy()
                )
            );
            $enchantingEntity->setProfitGradeBuy(
                $this->enchantingService->calculateProfitGrade($enchantingEntity->getProfitPercentageBuy())
            );

            $enchantingEntity->setComplete(
                $this->enchantingService->isComplete([
                    $enchantingEntity->getHigherEnchantmentItem()->getSellOrderPrice(),
                    $baseItem->getSellOrderPrice(),
                    $baseItem->getBuyOrderPrice(),
                    $enchantingEntity->getEnchantmentMaterial()->getBuyOrderPrice(),
                ])
            );
            $enchantingEntity->setCity($city);

            $this->enchantingRepository->createOrUpdate($enchantingEntity);
        }

        return self::SUCCESS;
    }
}