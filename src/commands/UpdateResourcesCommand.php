<?php

declare(strict_types=1);

namespace MZierdt\Albion\commands;

use MZierdt\Albion\AlbionDataAPI\ApiService;
use MZierdt\Albion\repositories\ResourceRepository;
use MZierdt\Albion\Service\ConfigService;
use MZierdt\Albion\Service\ProgressBarService;
use MZierdt\Albion\Service\UploadHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateResourcesCommand extends Command
{
    public function __construct(
        private readonly ApiService $apiService,
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

        $progressBar = ProgressBarService::getProgressBar(
            $output,
            is_countable($resourceList) ? count($resourceList) : 0
        );
        foreach ($resourceList as $resourceStats) {
            $progressBar->setMessage('Get Resource ' . $resourceStats['realName']);
            $progressBar->advance();
            $progressBar->display();
            $resourcesData = $this->apiService->getResources($resourceStats['realName']);
            $progressBar->setMessage('preparing resource ' . $resourceStats['realName']);
            $progressBar->display();
            $adjustedResources = $this->uploadHelper->adjustResources($resourcesData, $resourceStats);
            $progressBar->setMessage('Upload Resource ' . $resourceStats['realName'] . ' into Database');
            $progressBar->display();
            foreach ($adjustedResources as $adjustedResource) {
                $this->resourceRepository->createOrUpdate($adjustedResource);
            }
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
