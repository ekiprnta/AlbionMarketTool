<?php

namespace MZierdt\Albion\Service;

use DateTimeImmutable;

class TimeHelper
{
    public static function calculateAgeOfPrices(DateTimeImmutable $priceDate): int
    {
        $now = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', Date('Y-m-d H:i:s'));

        $dateDiff =  date_diff($now, $priceDate);
        return $dateDiff->d * 24 *60 + $dateDiff->h * 60 + $dateDiff->i;
    }
}