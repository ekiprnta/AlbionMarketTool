<?php

declare(strict_types=1);

namespace MZierdt\Albion\commands;

use MZierdt\Albion\AlbionMarket\BlackMarketTransportingService;
use MZierdt\Albion\Entity\AdvancedEntities\BlackMarketTransportEntity;
use MZierdt\Albion\repositories\AdvancedRepository\BlackMarketTransportingRepository;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\Service\ConfigService;
use MZierdt\Albion\Service\ProgressBarService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'market:bmTransport', description: 'Update Calculations for blackmarket/transporting')]
class UpdateBmTransportCommand extends Command
{
    public function __construct(
        private readonly BlackMarketTransportingService $bmtService,
        private readonly BlackMarketTransportingRepository $bmtRepository,
        private readonly ItemRepository $itemRepository,
        private readonly ConfigService $configService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $bmItems = $this->itemRepository->getItemsByLocationForBM('BLack Market');
        try {
            $amountConfig = $this->configService->getBlackMarketSells();
        } catch (\JsonException $jsonException) {
            $output->writeln($jsonException->getMessage());
            return self::FAILURE;
        }

        $city = 'Fort Sterling';
        $output->writeln('Updating Transport from ' . $city . '...');
        $this->updateCalculations($city, $output, $bmItems, $amountConfig);

        $city = 'Lymhurst';
        $output->writeln(PHP_EOL . 'Updating Transport from ' . $city . '...');
        $this->updateCalculations($city, $output, $bmItems, $amountConfig);

        $city = 'Bridgewatch';
        $output->writeln(PHP_EOL . 'Updating Transport from ' . $city . '...');
        $this->updateCalculations($city, $output, $bmItems, $amountConfig);

        $city = 'Martlock';
        $output->writeln(PHP_EOL . 'Updating Transport from ' . $city . '...');
        $this->updateCalculations($city, $output, $bmItems, $amountConfig);

        $city = 'Thetford';
        $output->writeln(PHP_EOL . 'Updating Transport from ' . $city . '...');
        $this->updateCalculations($city, $output, $bmItems, $amountConfig);

        $output->writeln(PHP_EOL . 'Done');
        return self::SUCCESS;
    }

    private function updateCalculations(
        string $city,
        OutputInterface $output,
        array $bmItems,
        array $amountConfig
    ): void {
        $cityItems = $this->itemRepository->getItemsByLocationForBM($city);
        $progressBar = ProgressBarService::getProgressBar($output, count($cityItems));
        $bmtEntities = [];
        foreach ($bmItems as $bmItem) {
            $bmtEntities[] = new BlackMarketTransportEntity($bmItem);
        }
        /** @var BlackMarketTransportEntity $bmtEntity */
        foreach ($bmtEntities as $bmtEntity) {

            $message = sprintf('Update bmtEntity: %s from %s', $bmtEntity->getBmItem()->getRealName(), $city);
            $progressBar->setMessage($message);
            $progressBar->advance();
            $progressBar->display();

            $this->bmtService->calculateBmtEntity(
                $bmtEntity,
                $cityItems,
                $amountConfig[$bmtEntity->getBmItem()->getTier()],
                $city
            );

            $this->bmtRepository->createOrUpdate($bmtEntity);
        }
    }
}
