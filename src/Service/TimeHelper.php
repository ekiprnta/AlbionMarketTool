<?php

namespace MZierdt\Albion\Service;

use DateTimeImmutable;
use DateTimeZone;

class TimeHelper
{

    public static function calculateAge(?string $dateString): int
    {
        $priceDate = self::calculateDateTimeImmutable($dateString);

        $now = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', Date('Y-m-d H:i:s'));

        $dateDiff = date_diff($now, $priceDate);
        return $dateDiff->d * 24 * 60 + $dateDiff->h * 60 + $dateDiff->i;
    }

    private static function calculateDateTimeImmutable(?string $dateString): DateTimeImmutable
    {
        if ($dateString === null) {
            return DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2000-00-00 00:00:00');
        }
        $dateString = str_replace('T', ' ', $dateString);
        return DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dateString, new DateTimeZone('Europe/London'));
    }
}