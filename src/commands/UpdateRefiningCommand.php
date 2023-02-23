<?php

declare(strict_types=1);

namespace MZierdt\Albion\commands;

use MZierdt\Albion\AlbionMarket\RefiningService;
use MZierdt\Albion\Entity\AdvancedEntities\RefiningEntity;
use MZierdt\Albion\repositories\AdvancedRepository\RefiningRepository;
use MZierdt\Albion\repositories\ResourceRepository;
use MZierdt\Albion\Service\ProgressBarService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'market:refining', description: 'Update Calculations for resource/refining')]
class UpdateRefiningCommand extends Command
{
    public function __construct(
        private readonly RefiningService $refiningService,
        private readonly RefiningRepository $refiningRepository,
        private readonly ResourceRepository $resourceRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $city = 'Fort Sterling';
        $output->writeln('Updating Refining from ' . $city . '...');
        $this->updateCalculations($city, $output);

        $city = 'Lymhurst';
        $output->writeln(PHP_EOL . 'Updating Refining from ' . $city . '...');
        $this->updateCalculations($city, $output);

//        $city = 'Bridgewatch'; Stoneblock is different so special is needed
//        $output->writeln(PHP_EOL . 'Updating Refining from ' . $city . '...');
//        $this->updateCalculations($city, $output);

        $city = 'Martlock';
        $output->writeln(PHP_EOL . 'Updating Refining from ' . $city . '...');
        $this->updateCalculations($city, $output);

        $city = 'Thetford';
        $output->writeln(PHP_EOL . 'Updating Refining from ' . $city . '...');
        $this->updateCalculations($city, $output);

        $output->writeln(PHP_EOL . 'Done');
        return self::SUCCESS;
    }

    private function updateCalculations(string $city, OutputInterface $output): void
    {
        $resources = $this->resourceRepository->getResourcesByBonusCity($city);
        $rawResources = $this->resourceRepository->getRawResourcesByBonusCity($city);
        $progressBar = ProgressBarService::getProgressBar($output, (count($resources) - 1));

        $refiningArray = [];
        foreach ($resources as $resource) {
            if ($resource->getTier() !== 20) {
                $refiningArray[] = new RefiningEntity($resource);
            }
        }
        /** @var RefiningEntity $refiningEntity */
        foreach ($refiningArray as $refiningEntity) {
            $message = sprintf(
                'Update refiningEntity: %s from %s',
                $refiningEntity->getRefinedResource()->getRealName(),
                $city
            );
            $progressBar->setMessage($message);
            $progressBar->advance();
            $progressBar->display();

            $refiningEntity = $this->refiningService->calculateRefiningEntity(
                $refiningEntity,
                $rawResources,
                $resources,
                $city
            );

            $this->refiningRepository->createOrUpdate($refiningEntity);
        }

        $progressBar->setMessage('Update in ' . $city . ' finished');
        $progressBar->finish();
    }

}
