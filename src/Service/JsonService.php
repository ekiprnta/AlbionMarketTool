<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

class JsonService
{
    private const PATH_TO_JSON = __DIR__ . '/NameData.json';

    public function getNameDataArray()
    {
        $json = file_get_contents(self::PATH_TO_JSON);
        $nameDataArray = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        dd($nameDataArray);
    }

}