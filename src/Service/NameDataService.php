<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

class NameDataService
{
    private const PATH_TO_JSON = __DIR__ . '/NameData.json';

    public static function getNameDataArray()
    {
        $json = file_get_contents(self::PATH_TO_JSON);
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }

}