<?php

declare(strict_types=1);

namespace MZierdt\Albion\commands;

use MZierdt\Albion\AlbionMarket\NoSpecCraftingService;
use MZierdt\Albion\Entity\AdvancedEntities\NoSpecEntity;
use MZierdt\Albion\repositories\AdvancedRepository\NoSpecRepository;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\repositories\MaterialRepository;
use MZierdt\Albion\Service\ProgressBarService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'market:noSpec', description: 'Update Calculations for noSpec/crafting')]
class UpdateNoSpecCraftingCommand extends Command
{
    public function __construct(
        private readonly NoSpecCraftingService $noSpecCraftingService,
        private readonly NoSpecRepository $noSpecRepository,
        private readonly ItemRepository $itemRepository,
        private readonly MaterialRepository $materialRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $city = 'Fort Sterling';
        $output->writeln('Updating NoSpecCrafting from ' . $city . '...');
        $this->updateCalculations($city, $output);

        $city = 'Lymhurst';
        $output->writeln(PHP_EOL . 'Updating NoSpecCrafting from ' . $city . '...');
        $this->updateCalculations($city, $output);

        $city = 'Bridgewatch';
        $output->writeln(PHP_EOL . 'Updating NoSpecCrafting from ' . $city . '...');
        $this->updateCalculations($city, $output);

        $city = 'Martlock';
        $output->writeln(PHP_EOL . 'Updating NoSpecCrafting from ' . $city . '...');
        $this->updateCalculations($city, $output);

        $city = 'Thetford';
        $output->writeln(PHP_EOL . 'Updating NoSpecCrafting from ' . $city . '...');
        $this->updateCalculations($city, $output);

        $output->writeln(PHP_EOL . 'Done');
        return self::SUCCESS;
    }

    private function updateCalculations(string $city, OutputInterface $output): void
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

        $progressBar = ProgressBarService::getProgressBar($output, count($noSpecEntities));
        /** @var NoSpecEntity $noSpecEntity */
        foreach ($noSpecEntities as $noSpecEntity) {
            $message = sprintf(
                'Update NoSpecEntity: %s from %s',
                $noSpecEntity->getSpecialItem()
                    ->getRealName(),
                $city
            );
            $progressBar->setMessage($message);
            $progressBar->advance();
            $progressBar->display();

            $this->noSpecCraftingService->calculateNoSpecEntity(
                $noSpecEntity,
                $defaultItems,
                $heartsAndSigils,
                $artifacts,
                $city
            );

            $this->noSpecRepository->createOrUpdate($noSpecEntity);
        }
        $progressBar->setMessage('Update in ' . $city . ' finished');
        $progressBar->finish();
    }
}
