<?php

declare(strict_types=1);

namespace MZierdt\Albion\commands;

use MZierdt\Albion\AlbionDataAPI\MaterialsApiService;
use MZierdt\Albion\repositories\MaterialRepository;
use MZierdt\Albion\Service\UploadHelper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'update:materials', description: 'update Prices of Materials')]
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
        $adjustedHearts = $this->uploadHelper->adjustMaterials($hearts, 'heartsAndSigils');
        foreach ($adjustedHearts as $adjustedHeart) {
            $adjustedHeart->setRealName($this->uploadHelper->calculateHeartRealName($adjustedHeart->getName()));
            $this->materialRepository->createOrUpdate($adjustedHeart);
        }

        $output->writeln('Updating Cape Artifacts...');
        $capeArtifacts = $this->materialsApiService->getCapeArtifacts();
        $adjustedCapeArtifacts = $this->uploadHelper->adjustMaterials($capeArtifacts, 'capeArtifacts');
        foreach ($adjustedCapeArtifacts as $adjustedCapeArtifact) {
            $this->materialRepository->createOrUpdate($adjustedCapeArtifact);
        }

        $output->writeln('Updating Royal Sigils...');
        $sigils = $this->materialsApiService->getRoyalSigils();
        $adjustedSigils = $this->uploadHelper->adjustMaterials($sigils, 'heartsAndSigils');
        foreach ($adjustedSigils as $adjustedSigil) {
            $this->materialRepository->createOrUpdate($adjustedSigil);
        }

        $output->writeln('Updating Tomes...');
        $tomes = $this->materialsApiService->getTomesOfInsight();
        $adjustedTomes = $this->uploadHelper->adjustMaterials($tomes, 'tomes');
        foreach ($adjustedTomes as $adjustedTome) {
            $this->materialRepository->createOrUpdate($adjustedTome);
        }

        $output->writeln($message);
        return self::SUCCESS;
    }
}
