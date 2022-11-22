<?php

declare(strict_types=1);

namespace MZierdt\Albion\commands;

use MZierdt\Albion\repositories\UploadRepository;
use MZierdt\Albion\Service\ApiService;
use MZierdt\Albion\Service\ConfigService;
use MZierdt\Albion\Service\ProgressBarService;
use MZierdt\Albion\Service\UploadService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateResourcesCommand extends Command
{
    public function __construct(
        private ApiService $apiService,
        private UploadRepository $uploadRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $message = 'successfully updated all Prices';
        try {
            $resourceList = ConfigService::getResourceConfig();
        } catch (\JsonException $jsonException) {
            $output->writeln($jsonException->getMessage());
            return self::FAILURE;
        }

        $progressBar = ProgressBarService::getProgressBar($output, count($resourceList));

        foreach ($resourceList as $resourceStats) {
            $progressBar->setMessage('Get Resource ' . $resourceStats['realName']);
            $progressBar->advance();
            $progressBar->display();
            $resourcesData = $this->apiService->getResource($resourceStats['realName']);
            $progressBar->setMessage('preparing resource ' . $resourceStats['realName']);
            $progressBar->display();
            $adjustedResources = UploadService::adjustResourceArray($resourcesData, $resourceStats);
            $progressBar->setMessage('Upload Resource ' . $resourceStats['realName'] . ' into Database');
            $progressBar->display();
            $this->uploadRepository->updatePricesFromResources($adjustedResources);
        }

        $output->writeln(PHP_EOL . $message);
        return self::SUCCESS;
    }

    protected function configure()
    {
        $this->setName('update:resource');
        $this->setDescription('update Prices of Resources');
        $this->setHelp('updates Prices of Resources');
    }
}
