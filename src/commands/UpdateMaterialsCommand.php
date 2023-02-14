<?php

declare(strict_types=1);

namespace MZierdt\Albion\commands;

use MZierdt\Albion\AlbionDataAPI\MaterialsApiService;
use MZierdt\Albion\repositories\MaterialRepository;
use MZierdt\Albion\Service\UploadHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateMaterialsCommand extends Command
{
    public function __construct(
        private readonly MaterialsApiService $materialsApiService,
        private readonly MaterialRepository $materialRepository,
        private readonly UploadHelper $uploadHelper,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $message = 'successfully updated all Prices';

        $output->writeln('Updating Materials...');
        $materials = $this->materialsApiService->getMaterials();

        $adjustedMaterials = $this->uploadHelper->adjustMaterials($materials, 'materials');

        foreach ($adjustedMaterials as $adjustedMaterial) {
            $this->materialRepository->createOrUpdate($adjustedMaterial);
        }

        $output->writeln('Updating Hearts...');
        $hearts = $this->materialsApiService->getHearts();
        $adjustedHearts = $this->uploadHelper->adjustMaterials($hearts, 'hearts');
        foreach ($adjustedHearts as $adjustedHeart) {
            $adjustedHeart->setRealName($this->getRealName($adjustedHeart->getName()));
            $this->materialRepository->createOrUpdate($adjustedHeart);
        }

        $output->writeln('Updating Cape Artifacts...');
        $hearts = $this->materialsApiService->getCapeArtifacts();
        $adjustedHearts = $this->uploadHelper->adjustMaterials($hearts, 'capeArtifacts');
        foreach ($adjustedHearts as $adjustedHeart) {
            $this->materialRepository->createOrUpdate($adjustedHeart);
        }

        return self::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setName('update:materials');
        $this->setDescription('update Prices of Materials');
        $this->setHelp('updates Prices of Materials');
    }

    private function getRealName(string $name): string
    {
        return match ($name) {
            'faction_forest_token_1' => 'Treeheart',
            'faction_highland_token_1' => 'Rockheart',
            'faction_steppe_token_1' => 'Beastheart',
            'faction_mountain_token_1' => 'Mountainheart',
            'faction_swamp_token_1' => 'Vineheart',
            'faction_caerleon_token_1' => 'Shadowheart',
            default => throw new \InvalidArgumentException('No Heart Found in setRealName ' . $name)
        };
    }
}
