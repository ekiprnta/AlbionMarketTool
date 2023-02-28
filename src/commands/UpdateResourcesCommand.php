<?php

declare(strict_types=1);

namespace MZierdt\Albion\commands;

use MZierdt\Albion\AlbionDataAPI\ResourceApiService;
use MZierdt\Albion\repositories\ResourceRepository;
use MZierdt\Albion\Service\ConfigService;
use MZierdt\Albion\Service\ProgressBarService;
use MZierdt\Albion\Service\UploadHelper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'update:resources', description: 'update Prices of Resource')]
class UpdateResourcesCommand extends Command
{
    public function __construct(
        private readonly ResourceApiService $resourceApiService,
        private readonly ResourceRepository $resourceRepository,
        private readonly ConfigService $configService,
        private readonly UploadHelper $uploadHelper,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $message = 'successfully updated all Prices';
        try {
            $resourceList = $this->configService->getResourceConfig();
        } catch (\JsonException $jsonException) {
            $output->writeln($jsonException->getMessage());
            return self::FAILURE;
        }
        unset($resourceList['stoneBlock']); // Todo add Stone

        $output->writeln('Updating Resources...');
        $progressBar = ProgressBarService::getProgressBar(
            $output,
            is_countable($resourceList) ? count($resourceList) : 0
        );
        foreach ($resourceList as $resourceStats) {
            $progressBar->setMessage('Get Resource ' . $resourceStats['realName']);
            $progressBar->advance();
            $progressBar->display();
            $resourcesData = $this->resourceApiService->getResources($resourceStats['realName']);
            $progressBar->setMessage('preparing resource ' . $resourceStats['realName']);
            $progressBar->display();
            $adjustedResources = $this->uploadHelper->adjustResources($resourcesData, $resourceStats);
            $progressBar->setMessage('Upload Resource ' . $resourceStats['realName'] . ' into Database');
            $progressBar->display();
            foreach ($adjustedResources as $adjustedResource) {
                $this->resourceRepository->createOrUpdate($adjustedResource);
            }
        }

        try {
            $rawResourceConfig = $this->configService->getRawResourceConfig();
        } catch (\JsonException $jsonException) {
            $output->writeln($jsonException->getMessage());
            return self::FAILURE;
        }
        unset($rawResourceConfig['stoneBlock']); // Todo add Stone

        $output->writeln(PHP_EOL . 'Updating Raw Resources...');
        $progressBar = ProgressBarService::getProgressBar(
            $output,
            is_countable($rawResourceConfig) ? count($rawResourceConfig) : 0
        );
        foreach ($rawResourceConfig as $rawResourceStat) {
            $progressBar->setMessage('Get raw ' . $rawResourceStat['realName']);
            $progressBar->advance();
            $progressBar->display();
            $rawResourcesData = $this->resourceApiService->getResources($rawResourceStat['realName']);
            $progressBar->setMessage('preparing raw ' . $rawResourceStat['realName']);
            $progressBar->display();
            $adjustedRawResources = $this->uploadHelper->adjustResources($rawResourcesData, $rawResourceStat, true);
            $progressBar->setMessage('Upload raw ' . $rawResourceStat['realName'] . ' into Database');
            $progressBar->display();
            foreach ($adjustedRawResources as $adjustedRawResource) {
                $this->resourceRepository->createOrUpdate($adjustedRawResource);
            }
        }

        $output->writeln(PHP_EOL . $message);
        return self::SUCCESS;
    }
}
