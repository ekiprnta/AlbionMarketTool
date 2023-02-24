<?php

declare(strict_types=1);

namespace MZierdt\Albion\commands;

use MZierdt\Albion\AlbionMarket\EnchantingService;
use MZierdt\Albion\Entity\AdvancedEntities\EnchantingEntity;
use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\repositories\AdvancedRepository\EnchantingRepository;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\repositories\MaterialRepository;
use MZierdt\Albion\Service\ConfigService;
use MZierdt\Albion\Service\ProgressBarService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'market:enchanting', description: 'Update Calculations for noSpec/enchanting')]
class UpdateEnchantingCommand extends Command
{
    public function __construct(
        private readonly EnchantingService $enchantingService,
        private readonly EnchantingRepository $enchantingRepository,
        private readonly ItemRepository $itemRepository,
        private readonly MaterialRepository $materialRepository,
        private readonly ConfigService $configService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $bmItems = $this->itemRepository->getItemsByLocationForBM('Black Market');
        $bmSellAmount = $this->configService->getBlackMarketSells();

        $city = 'Fort Sterling';
        $output->writeln('Updating Enchanting from ' . $city . '...');
        $this->updateCalculations($city, $bmItems, $bmSellAmount, $output);

        $city = 'Lymhurst';
        $output->writeln(PHP_EOL . 'Updating Enchanting from ' . $city . '...');
        $this->updateCalculations($city, $bmItems, $bmSellAmount, $output);

        $city = 'Bridgewatch';
        $output->writeln(PHP_EOL . 'Updating Enchanting from ' . $city . '...');
        $this->updateCalculations($city, $bmItems, $bmSellAmount, $output);

        $city = 'Martlock';
        $output->writeln(PHP_EOL . 'Updating Enchanting from ' . $city . '...');
        $this->updateCalculations($city, $bmItems, $bmSellAmount, $output);

        $city = 'Thetford';
        $output->writeln(PHP_EOL . 'Updating Enchanting from ' . $city . '...');
        $this->updateCalculations($city, $bmItems, $bmSellAmount, $output);

        $output->writeln(PHP_EOL . 'Done');
        return self::SUCCESS;
    }

    private function updateCalculations(
        string $city,
        array $bmItems,
        array $bmSellsConfig,
        OutputInterface $output
    ): void {
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
        $progressBar = ProgressBarService::getProgressBar($output, count($enchantingEntities));

        /** @var EnchantingEntity $enchantingEntity */
        foreach ($enchantingEntities as $enchantingEntity) {
            $message = sprintf(
                'Update refiningEntity: %s from %s',
                $enchantingEntity->getBaseItem()
                    ->getRealName(),
                $city
            );
            $progressBar->setMessage($message);
            $progressBar->advance();
            $progressBar->display();

            $this->enchantingService->calculateEnchantingEntity(
                $enchantingEntity,
                $bmItems,
                $materials,
                $bmSellsConfig,
                $city
            );

            $this->enchantingRepository->createOrUpdate($enchantingEntity);
        }
        $progressBar->setMessage('Update in ' . $city . ' finished');
        $progressBar->finish();
    }
}
