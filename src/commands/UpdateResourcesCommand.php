<?php

declare(strict_types=1);

namespace MZierdt\Albion\commands;

use MZierdt\Albion\repositories\ResourceRepository;
use MZierdt\Albion\Service\ApiService;
use MZierdt\Albion\Service\ConfigService;
use MZierdt\Albion\Service\ProgressBarService;
use MZierdt\Albion\Service\UploadHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateResourcesCommand extends Command
{
    public function __construct(
        private ApiService $apiService,
        private ResourceRepository $resourceRepository,
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
        unset($resourceList['stoneBlock']); // Todo add Stone

        $progressBar = ProgressBarService::getProgressBar(
            $output,
            is_countable($resourceList) ? count($resourceList) : 0
        );
        foreach ($resourceList as $resourceStats) {
            $progressBar->setMessage('Get Resource ' . $resourceStats['realName']);
            $progressBar->advance();
            $progressBar->display();
            $resourcesData = $this->apiService->getResource($resourceStats['realName']);
            $progressBar->setMessage('preparing resource ' . $resourceStats['realName']);
            $progressBar->display();
            $adjustedResources = UploadHelper::adjustResourceArray($resourcesData, $resourceStats);
            dd($resourcesData);
            $progressBar->setMessage('Upload Resource ' . $resourceStats['realName'] . ' into Database');
            $progressBar->display();
            $this->resourceRepository->updatePricesFromResources($adjustedResources);
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
