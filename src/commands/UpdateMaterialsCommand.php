<?php

declare(strict_types=1);

namespace MZierdt\Albion\commands;

use MZierdt\Albion\repositories\MaterialRepository;
use MZierdt\Albion\Service\ApiService;
use MZierdt\Albion\Service\UploadHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateMaterialsCommand extends Command
{
    public function __construct(
        private readonly ApiService $apiService,
        private readonly MaterialRepository $materialRepository,
        private readonly UploadHelper $uploadHelper,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $message = 'successfully updated all Prices';

        $materials = $this->apiService->getMaterials();

        $adjustedMaterials = $this->uploadHelper->adjustMaterials($materials);

        foreach ($adjustedMaterials as $adjustedMaterial) {
            $this->materialRepository->createOrUpdate($adjustedMaterial);
        }
        return self::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setName('update:materials');
        $this->setDescription('update Prices of Materials');
        $this->setHelp('updates Prices of Materials');
    }
}
