<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

use DateTimeImmutable;

class TimeService
{
    private const FIVE_DAYS_AGO = '-5 days';

    public static function getFiveDaysAgo(DateTimeImmutable $now): DateTimeImmutable
    {
        return $now->modify(self::FIVE_DAYS_AGO);
    }
}
