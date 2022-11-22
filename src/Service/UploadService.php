<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

use MZierdt\Albion\repositories\UploadRepository;
use Symfony\Component\Console\Output\OutputInterface;

class UploadService
{
    public function __construct(
        private ApiService $apiService,
        private UploadRepository $uploadRepository,
    ) {
    }



    public function updateJournalPricesInAlbionDb(OutputInterface $output): void
    {
        $journalList = ConfigService::getJournalConfig();
        $progressBar = ProgressBarService::getProgressBar($output, count($journalList['names']));

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
    public static function adjustResourceArray(array $resourceData, array $resourceStats): array
    {
        $adjustedResourceArray = [];
        foreach ($resourceData as $resource) {
            $nameAndTier = TierService::splitIntoTierAndName($resource['item_id']);
            $name = UploadService::getResourceName($nameAndTier['name']);
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

    public static function adjustJournals(array $journalData, array $journalStats): array
    {
        $adjustedJournalsArray = [];
        foreach ($journalData as $journal) {
            $nameAndTier = TierService::splitIntoTierAndName($journal['item_id']);
            $stats = $journalStats[$nameAndTier['tier']];
            [$name, $fillStatus] = explode('_', $nameAndTier['name']);
            $adjustedJournalsArray[] = [
                'tier' => $nameAndTier['tier'],
                'name' => $nameAndTier['name'],
                'city' => $journal['city'],
                'fameToFill' => $stats['fameToFill'],
                'sellOrderPrice' => $journal['sell_price_min'],
                'sellOrderPriceDate' => $journal['sell_price_min_date'],
                'buyOrderPrice' => $journal['buy_price_max'],
                'buyOrderPriceDate' => $journal['buy_price_max_date'],
                'weight' => $stats['weight'],
                'fillStatus' => $fillStatus,
                'class' => $name,
            ];
        }
        return $adjustedJournalsArray;
    }

    public static function adjustItems(array $itemData, array $itemStats): array
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

    private static function getResourceName(string $name): string
    {
        if (count_chars($name) > 10) {
            return substr($name,0, -7);
        }
        return $name;
    }
}
