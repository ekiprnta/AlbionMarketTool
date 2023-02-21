<?php

declare(strict_types=1);

namespace unit\Service;

use DateTimeImmutable;
use MZierdt\Albion\Service\TimeService;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class TimeServiceTest extends TestCase
{
    use ProphecyTrait;

    public function testGetFiveDaysAgo(): void
    {
        $now = new DateTimeImmutable();

        self::assertEquals($now->modify('-5 days'), TimeService::getFiveDaysAgo($now));
    }
}