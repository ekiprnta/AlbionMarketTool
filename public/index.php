<?php

declare(strict_types=1);


use MZierdt\Albion\HttpClient;
use MZierdt\Albion\Service\ApiService;

$uri = $_SERVER['REQUEST_URI'];
echo 'bla';


$httpClient = new HttpClient();
$apiService = new ApiService($httpClient);

echo $apiService->getResource('planks');