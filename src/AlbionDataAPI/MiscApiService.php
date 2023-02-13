<?php

declare(strict_types=1);

namespace MZierdt\Albion\AlbionDataAPI;

class MiscApiService extends ApiService
{
    private const URL_GOLD = 'https://www.albion-online-data.com/api/v2/stats/gold/';
    private const JOURNAL_TIERS_WITH_PLACEHOLDER = 'T2_%s_FULL,T3_%s_FULL,T4_%s_FULL,T5_%s_FULL,T6_%s_FULL,T7_%s_FULL,T8_%s_FULL,T2_%s_EMPTY,T3_%s_EMPTY,T4_%s_EMPTY,T5_%s_EMPTY,T6_%s_EMPTY,T7_%s_EMPTY,T8_%s_EMPTY';

    public function getJournals(string $journalType)
    {
        $apiUrl = $this->apiUrlAssembler($journalType, self::JOURNAL_TIERS_WITH_PLACEHOLDER);

        return $this->get($apiUrl, ['locations' => self::CITY_ALL]);
    }

    public function getGoldPrice(): int
    {
        $goldPriceArray = $this->get(self::URL_GOLD, ['count' => 1]);
        return $goldPriceArray[0]['price'];
    }
}