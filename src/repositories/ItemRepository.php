<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use InvalidArgumentException;
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

    public function getItemsAsItemEntityFromBonusCity(string $city): array
    {
        $allWarriorItems = $this->getAllItemsFromCsvAsItemEntity(self::WARRIOR);
        $allMageItems = $this->getAllItemsFromCsvAsItemEntity(self::MAGE);
        $allHunterItems = $this->getAllItemsFromCsvAsItemEntity(self::HUNTER);

        return $this->filterByBonusCity($city, $allWarriorItems, $allMageItems, $allHunterItems);
    }

    private function getReaderFromType(string $type): Reader
    {
        $path = match ($type) {
            self::WARRIOR => self::PATH_TO_CSV_WARRIOR,
            self::MAGE => self::PATH_TO_CSV_MAGE,
            self::HUNTER => self::PATH_TO_CSV_HUNTER,
            default => throw new InvalidArgumentException('wrong Item type in ItemRepository')
        };

        $csv = Reader::createFromPath($path, 'rb');
        $csv->setHeaderOffset(0);
        return $csv;
    }

    private function getAllItemsFromCsvAsItemEntity(string $type): array
    {
        $reader = $this->getReaderFromType($type);
        $allItemInformation = $reader->getRecords();
        return $this->dataAsItemEntity($allItemInformation);
    }

    private function dataAsItemEntity(Iterator $allItemInformation): array
    {
        $itemStack = [];
        foreach ($allItemInformation as $item) {
            $itemStack[] = new ItemEntity($item);
        }
        return $itemStack;
    }

    private function filterByBonusCity(
        string $city,
        array $allWarriorItems,
        array $allMageItems,
        array $allHunterItems
    ): array
    {
        $filteredItems = [];
        /** @var ItemEntity $warriorItem */
        foreach ($allWarriorItems as $warriorItem) {
            if ($warriorItem->getBonusCity() === $city) {
                $filteredItems[] = $warriorItem;
            }
        }
        /** @var ItemEntity $mageItem */
        foreach ($allMageItems as $mageItem) {
            if ($mageItem->getBonusCity() === $city) {
                $filteredItems[] = $mageItem;
            }
        }
        /** @var ItemEntity $hunterItem */
        foreach ($allHunterItems as $hunterItem) {
            if ($hunterItem->getBonusCity() === $city) {
                $filteredItems[] = $hunterItem;
            }
        }
        return $filteredItems;
    }
}
