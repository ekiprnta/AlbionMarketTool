<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use Iterator;
use League\Csv\Reader;
use MZierdt\Albion\Entity\ItemEntity;

class ItemRepository
{
    private const PATH_TO_CSV_WARRIOR = __DIR__ . '/../../assets/warrior.csv';
    private const PATH_TO_CSV_MAGE = __DIR__ . '/../../assets/mage.csv';
    private const PATH_TO_CSV_HUNTER = __DIR__ . '/../../assets/hunter.csv';
    private const WARRIOR = 'warrior';
    private const MAGE = 'mage';
    private const HUNTER = 'hunter';

    public function getItemsAsItemEntityFromCity(string $city)
    {
        $readerWarrior = $this->getReaderFromType(self::WARRIOR);
        $readerMage = $this->getReaderFromType(self::MAGE);
        $readerHunter = $this->getReaderFromType(self::HUNTER);

        $allWarriorItems = $this->getAllItemsFromCsvAsItemEntity(self::WARRIOR);
        $allMageItems = $this->getAllItemsFromCsvAsItemEntity(self::MAGE);
        $allHunterItems = $this->getAllItemsFromCsvAsItemEntity(self::HUNTER);

    }

    private function getReaderFromType(string $type): Reader
    {
        $path = match ($type) {
            self::WARRIOR => self::PATH_TO_CSV_WARRIOR,
            self::MAGE => self::PATH_TO_CSV_MAGE,
            self::HUNTER => self::PATH_TO_CSV_HUNTER,
        };

        $csv = Reader::createFromPath($path,'rb');
        $csv->setHeaderOffset(0);
        return $csv;
    }

    private function getAllItemsFromCsvAsItemEntity(string $type): array
    {
        $reader = $this->getReaderFromType($type);
        $allItemInformation = $reader->getRecords();
        return $this->dataAsItemEntity($allItemInformation);
    }

    private function dataAsItemEntity(Iterator $allItemInformation)
    {
        $itemStack = [];
        foreach ($allItemInformation as $item) {
            $itemStack[] = new ItemEntity($item);
        }
        return $itemStack;
    }
}