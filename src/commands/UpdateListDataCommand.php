<?php

declare(strict_types=1);

namespace MZierdt\Albion\commands;

use MZierdt\Albion\AlbionMarket\ListDataService;
use MZierdt\Albion\Entity\AdvancedEntities\ListDataEntity;
use MZierdt\Albion\repositories\AdvancedRepository\ListDataRepository;
use MZierdt\Albion\repositories\ResourceRepository;
use MZierdt\Albion\Service\ProgressBarService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'market:listData', description: 'Update Calculations for info')]
class UpdateListDataCommand extends Command
{
    public function __construct(
        private readonly ListDataService $listDataService,
        private readonly ListDataRepository $listDataRepository,
        private readonly ResourceRepository $resourceRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $type = 'Resources';
        $output->writeln('Updating ListData ' . $type . '...');
        $fortSterlingResources = $this->resourceRepository->getRawResourcesByCity('Fort Sterling');
        $lymhurstResources = $this->resourceRepository->getRawResourcesByCity('Lymhurst');
        $bridgewatchResources = $this->resourceRepository->getRawResourcesByCity('Bridgewatch');
        $martlockResources = $this->resourceRepository->getRawResourcesByCity('Martlock');
        $thetfordResources = $this->resourceRepository->getRawResourcesByCity('Thetford');
        $this->getListDataEntities(
            $type,
            $fortSterlingResources,
            $lymhurstResources,
            $bridgewatchResources,
            $martlockResources,
            $thetfordResources,
            $output
        );

        $type = 'Raw Resources';
        $output->writeln(PHP_EOL . 'Updating ListData ' . $type . '...');
        $fortSterlingRawResources = $this->resourceRepository->getResourcesByCity('Fort Sterling');
        $lymhurstRawResources = $this->resourceRepository->getResourcesByCity('Lymhurst');
        $bridgewatchRawResources = $this->resourceRepository->getResourcesByCity('Bridgewatch');
        $martlockRawResources = $this->resourceRepository->getResourcesByCity('Martlock');
        $thetfordRawResources = $this->resourceRepository->getResourcesByCity('Thetford');
        $this->getListDataEntities(
            $type,
            $fortSterlingRawResources,
            $lymhurstRawResources,
            $bridgewatchRawResources,
            $martlockRawResources,
            $thetfordRawResources,
            $output
        );

        $output->writeln(PHP_EOL . 'Done');
        return self::SUCCESS;
    }

    private function getListDataEntities(
        string $type,
        array $fortSterlingResources,
        array $lymhurstResources,
        array $bridgewatchResources,
        array $martlockResources,
        array $thetfordResources,
        OutputInterface $output,
    ): void {
        $listDataEntities = [];
        foreach ($fortSterlingResources as $fortSterlingItem) {
            $listDataEntities[] = new ListDataEntity($fortSterlingItem);
        }
        $progressBar = ProgressBarService::getProgressBar($output, count($listDataEntities));

        /** @var ListDataEntity $listDataEntity */
        foreach ($listDataEntities as $listDataEntity) {
            $message = sprintf('Update ListDataEntity: %s', $listDataEntity->getFortsterlingObject()->getRealName());
            $progressBar->setMessage($message);
            $progressBar->advance();
            $progressBar->display();

            $this->listDataService->calculateListDataEntity(
                $listDataEntity,
                $lymhurstResources,
                $bridgewatchResources,
                $martlockResources,
                $thetfordResources,
                $type
            );

            $this->listDataRepository->createOrUpdate($listDataEntity);
        }
        $progressBar->setMessage('Update in finished');
        $progressBar->finish();
    }
}
