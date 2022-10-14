<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity;

class JournalEntity
{
    private const T2_BOOK_FAME = 900;
    private const T3_BOOK_FAME = 1800;
    private const T4_BOOK_FAME = 3600;
    private const T5_BOOK_FAME = 7200;
    private const T6_BOOK_FAME = 14400;
    private const T7_BOOK_FAME = 28380;
    private const T8_BOOK_FAME = 58590;

    private int $tier;
    private string $type;
    private int $emptyPrice;
    private int $fullPrice;
    private int $fameToFill; // how much fame is needed to fill
}