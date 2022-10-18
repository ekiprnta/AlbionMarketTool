<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use League\Csv\Reader;
use MZierdt\Albion\Entity\ResourceEntity;

class ResourceRepository
{
    private const PATH_TO_CSV = __DIR__ . '/../../assets/ressourcen.csv';

    public function __construct()
    {
    }

    public function getResourcesAsResourceEntity(): ResourceEntity
    {
        $reader = $this->getReader();

        $allResourceInformation = $reader->getRecords();
        $this->dataAsResourceEntity($allResourceInformation);


        return new ResourceEntity();
    }

    private function getReader(): Reader
    {
        $csv = Reader::createFromPath(self::PATH_TO_CSV, 'rb');
        $csv->setHeaderOffset(0);
        return $csv;
    }

    private function dataAsResourceEntity(iterable $allResourceInformation)
    {
        $metalBarStack = [];
        $planksStack = [];
        $clothStack = [];
        $leatherStack = [];
        foreach ($allResourceInformation as $resource) {
            dd($resource);
            $lowerCaseItemId = strtolower($resource['itemId']);
            if (str_contains($lowerCaseItemId, 'metalbar')) {
                $metalBarStack[] = new ResourceEntity($resource);
            }
            if (str_contains($lowerCaseItemId, 'planks')) {
                $planksStack[] = new ResourceEntity($resource);
            }
            if (str_contains($lowerCaseItemId, 'cloth')) {
                $clothStack[] = new ResourceEntity($resource);
            }
            if (str_contains($lowerCaseItemId, 'leather')) {
                $leatherStack[] = new ResourceEntity($resource);
            }
        }
        return [
            'metalBar' => $metalBarStack,
            'planks' => $planksStack,
            'cloth' => $clothStack,
            'leather' => $leatherStack
        ];
    }
}