<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity;

use DateTimeImmutable;
use DateTimeZone;

class JournalEntity
{
    public const JOURNAL_WARRIOR = 'JOURNAL_WARRIOR';
    public const JOURNAL_MAGE = 'JOURNAL_MAGE';
    public const JOURNAL_HUNTER = 'JOURNAL_HUNTER';

    private string $tier;
    private string $name;
    private string $city;
    private int $fameToFill;
    private int $sellOrderPrice;
    private DateTimeImmutable $sellOrderPriceDate;
    private int $buyOrderPrice;
    private DateTimeImmutable $buyOrderPriceDate;
    private float $weight;
    private string $fillStatus; //full empty
    private string $class; //warrior mage

    public function __construct(array $journalData)
    {
        $sellOrderPriceDate = $this->getDateTimeImmutable($journalData['sellOrderPriceDate']);
        $buyOrderPriceDate = $this->getDateTimeImmutable($journalData['buyOrderPriceDate']);

        $this->tier = $journalData['tier'];
        $this->name = $journalData['name'];
        $this->city = $journalData['city'];
        $this->fameToFill = (int) $journalData['fameToFill'];
        $this->sellOrderPrice = (int) $journalData['sellOrderPrice'];
        $this->sellOrderPriceDate = $sellOrderPriceDate;
        $this->buyOrderPrice = (int) $journalData['buyOrderPrice'];
        $this->buyOrderPriceDate = $buyOrderPriceDate;
        $this->weight = (float) $journalData['weight'];
        $this->fillStatus = $journalData['fillStatus'];
        $this->class = $journalData['class'];
    }

    public function getTier(): string
    {
        return $this->tier;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getFameToFill(): int
    {
        return $this->fameToFill;
    }

    public function getSellOrderPrice(): int
    {
        return $this->sellOrderPrice;
    }

    public function getSellOrderPriceDate(): DateTimeImmutable
    {
        return $this->sellOrderPriceDate;
    }

    public function getBuyOrderPrice(): int
    {
        return $this->buyOrderPrice;
    }

    public function getBuyOrderPriceDate(): DateTimeImmutable
    {
        return $this->buyOrderPriceDate;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function getFillStatus(): string
    {
        return $this->fillStatus;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    private function getDateTimeImmutable(?string $date): DateTimeImmutable|bool
    {
        if ($date === null) {
            return DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2000-00-00 00:00:00');
        }
        $date = str_replace('T', ' ', $date);
        return DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $date, new DateTimeZone('Europe/London'));
    }
}
