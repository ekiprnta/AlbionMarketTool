<?php

declare(strict_types=1);

namespace MZierdt\Albion\Entity;

use DateTimeImmutable;
use DateTimeZone;
use MZierdt\Albion\Service\TimeHelper;

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
    private int $sellOrderAge;
    private int $buyOrderPrice;
    private int $buyOrderAge;
    private float $weight;
    private string $fillStatus; //full empty
    private string $class; //warrior mage

    public function __construct(array $journalData)
    {
        $this->tier = $journalData['tier'];
        $this->name = $journalData['name'];
        $this->city = $journalData['city'];
        $this->fameToFill = (int) $journalData['fameToFill'];
        $this->sellOrderPrice = (int) $journalData['sellOrderPrice'];
        $this->sellOrderAge = TimeHelper::calculateAge($journalData['sellOrderPriceDate']);
        $this->buyOrderPrice = (int) $journalData['buyOrderPrice'];
        $this->buyOrderAge = TimeHelper::calculateAge($journalData['buyOrderPriceDate']);
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

    public function getSellOrderAge(): int
    {
        return $this->sellOrderAge;
    }

    public function getBuyOrderPrice(): int
    {
        return $this->buyOrderPrice;
    }

    public function getBuyOrderAge(): int
    {
        return $this->buyOrderAge;
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
}
