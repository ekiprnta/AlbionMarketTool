<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\Entity\JournalEntity;
use MZierdt\Albion\Entity\ResourceEntity;
use MZierdt\Albion\repositories\UploadRepository;
use Symfony\Component\Console\Output\OutputInterface;

class UploadService
{

    public function __construct(
        private ApiService $apiService,
        private UploadRepository $uploadRepository,
    ) {
    }


    /**
     * @throws \JsonException
     */
    public function updateJournalPricesInAlbionDb(OutputInterface $output): void
    {
        $journalList = ConfigService::getJournalConfig();
        $progressBar = ProgressBarService::getProgressBar($output, 210);
        foreach ($journalList['names'] as $journalNames) {
            $progressBar->setMessage('Get Resource ' . $journalNames);
            $progressBar->advance();
            $progressBar->display();
            $journalsData = $this->apiService->getJournals($journalNames);
            $progressBar->setMessage('preparing resource ' . $journalNames);
            $progressBar->display();
            $adjustedJournals = $this->adjustJournals($journalsData, $journalList['stats']);
            $progressBar->setMessage('Upload Resource ' . $journalNames . ' into Database');
            $progressBar->display();
            $this->uploadRepository->updatePricesFromJournals($adjustedJournals);
        }
    }


    /**
     * @throws \JsonException
     */
    public function updateResourcePricesInAlbionDb(OutputInterface $output): void
    {
        $resourceList = ConfigService::getResourceConfig();
        $progressBar = ProgressBarService::getProgressBar($output, count($resourceList));
        foreach ($resourceList as $resourceStats) {
            $progressBar->setMessage('Get Resource ' . $resourceStats['realName']);
            $progressBar->advance();
            $progressBar->display();
            $resourcesData = $this->apiService->getResource($resourceStats['realName']);
            $progressBar->setMessage('preparing resource ' . $resourceStats['realName']);
            $progressBar->display();
            $adjustedResources = $this->adjustResourceArray($resourcesData, $resourceStats);
            $progressBar->setMessage('Upload Resource ' . $resourceStats['realName'] . ' into Database');
            $progressBar->display();
            $this->uploadRepository->updatePricesFromResources($adjustedResources);
        }
    }


    /**
     * @throws \JsonException
     */
    public function updateItemPricesInAlbionDb(OutputInterface $output): void
    {
        $itemList = ConfigService::getItemConfig();
        $progressBar = ProgressBarService::getProgressBar($output, count($itemList));

        foreach ($itemList as $itemStats) {
            $progressBar->setMessage('Get Item:' . $itemStats['realName']);
            $progressBar->advance();
            $progressBar->display();
            $itemsData = $this->apiService->getItems($itemStats['realName']);
            $progressBar->setMessage('preparing Item' . $itemStats['realName']);
            $progressBar->display();
            $adjustedItems = $this->adjustItems($itemsData, $itemStats);
            $progressBar->setMessage('Upload Item ' . $itemStats['realName'] . ' into Database');
            $progressBar->display();
            $this->uploadRepository->updatePricesFromItem($adjustedItems);
        }
    }


    private function adjustResourceArray(array $resourceData, array $resourceStats): array
    {
        $adjustedResourceArray = [];
        foreach ($resourceData as $resource) {
            $nameAndTier = TierService::splitIntoTierAndName($resource['item_id']);
            $name = $this->getResourceName($nameAndTier['name']);
            $adjustedResourceArray[] = [
                'tier' => $nameAndTier['tier'],
                'name' => $name,
                'city' => $resource['city'],
                'realName' => $resourceStats['realName'],
                'sellOrderPrice' => $resource['sell_price_min'],
                'sellOrderPriceDate' => $resource['sell_price_min_date'],
                'buyOrderPrice' => $resource['buy_price_max'],
                'buyOrderPriceDate' => $resource['buy_price_max_date'],
                'bonusCity' => $resourceStats['bonusCity'],
            ];
        }
        return $adjustedResourceArray;
    }

    private function adjustJournals(array $journals, array $journalStats): array
    {
        $adjustedJournalsArray = [];
        foreach ($journals as $journal) {
            $nameAndTier = TierService::splitIntoTierAndName($journal['item_id']);
            $stats = ConfigService::getStatsJournals($nameAndTier['tier']);
            $split = explode('_', $nameAndTier['name']);
            $adjustedJournalsArray[] = [
                'tier' => $nameAndTier['tier'],
                'name' => implode('_', [$split[0], $split[1]]),
                'city' => $journal['city'],
                'fameToFill' => $stats['fameToFill'],
                'sellOrderPrice' => $journal['sell_price_min'],
                'sellOrderPriceDate' => $journal['sell_price_min_date'],
                'buyOrderPrice' => $journal['buy_price_max'],
                'buyOrderPriceDate' => $journal['buy_price_max_date'],
                'weight' => $stats['weight'],
                'fillStatus' => $split[2],
                'class' => $split[1],
            ];
        }
        return $adjustedJournalsArray;
    }

    private function getResourceName(string $name): string
    {
        if (str_contains($name, '_level1')) {
            $resourceName = str_replace('_level1', '', $name);
        }
        if (str_contains($name, '_level2')) {
            $resourceName = str_replace('_level2', '', $name);
        }
        if (str_contains($name, '_level3')) {
            $resourceName = str_replace('_level3', '', $name);
        }
        return $resourceName ?? $name;
    }

    private function adjustItems(array $itemData, array $itemStats): array
    {
        $adjustedItems = [];
            foreach ($itemData as $item) {
                $nameAndTier = TierService::splitIntoTierAndName($item['item_id']);
                $adjustedItems[] = [
                    'tier' => $nameAndTier['tier'],
                    'name' => $nameAndTier['name'],
                    'weaponGroup' => $itemStats['weaponGroup'],
                    'realName' => $itemStats['realName'],
                    'class' => $itemStats['class'],
                    'city' => $item['city'],
                    'quality' => $item['quality'],
                    'sellOrderPrice' => $item['sell_price_min'],
                    'sellOrderPriceDate' => $item['sell_price_min_date'],
                    'buyOrderPrice' => $item['buy_price_max'],
                    'buyOrderPriceDate' => $item['buy_price_max_date'],
                    'primaryResource' => $itemStats['primaryResource'],
                    'primaryResourceAmount' => $itemStats['primaryResourceAmount'],
                    'secondaryResource' => $itemStats['secondaryResource'],
                    'secondaryResourceAmount' => $itemStats['secondaryResourceAmount'],
                    'bonusCity' => $itemStats['bonusCity'],
                    'fameFactor' => null,
                ];
            }
        return $adjustedItems;
    }
}
