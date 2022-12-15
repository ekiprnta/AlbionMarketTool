<?php

namespace MZierdt\Albion\commands;

use MZierdt\Albion\repositories\RawResourceRepository;
use MZierdt\Albion\repositories\ResourceRepository;
use MZierdt\Albion\Service\ApiService;
use MZierdt\Albion\Service\ConfigService;
use MZierdt\Albion\Service\ProgressBarService;
use MZierdt\Albion\Service\UploadHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateRawResourcesCommand extends Command
{
    public function __construct(
        private ApiService $apiService,
        private RawResourceRepository $rawResourceRepository,
        private ConfigService $configService,
        private UploadHelper $uploadHelper,
    ) {
        parent::__construct();
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $message = 'successfully updated all Prices';
        try {
            $rawResourceConfig = $this->configService->getRawResourceConfig();
        } catch (\JsonException $jsonException) {
            $output->writeln($jsonException->getMessage());
            return self::FAILURE;
        }
        unset($rawResourceConfig['stoneBlock']); // Todo add Stone

        $progressBar = ProgressBarService::getProgressBar(
            $output,
            is_countable($rawResourceConfig) ? count($rawResourceConfig) : 0
        );
        foreach ($rawResourceConfig as $rawResourceStat) {
            $progressBar->setMessage('Get raw ' . $rawResourceStat['realName']);
            $progressBar->advance();
            $progressBar->display();
            $rawResourcesData = $this->apiService->getResources($rawResourceStat['realName']);
            $progressBar->setMessage('preparing raw ' . $rawResourceStat['realName']);
            $progressBar->display();
            $adjustedRawResources = $this->uploadHelper->adjustResourceArray($rawResourcesData, $rawResourceStat);
            $progressBar->setMessage('Upload raw ' . $rawResourceStat['realName'] . ' into Database');
            $progressBar->display();
            $this->rawResourceRepository->updatePricesFromRawResources($adjustedRawResources);
        }

        $output->writeln(PHP_EOL . $message);
        return self::SUCCESS;
    }

    protected function configure()
    {
        $this->setName('update:raw');
        $this->setDescription('update Prices of raw Resources');
        $this->setHelp('updates Prices of raw Resources');
    }
}