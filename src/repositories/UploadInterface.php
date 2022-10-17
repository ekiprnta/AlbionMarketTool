<?php

declare(strict_types=1);

namespace MZierdt\Albion\repositories;

use MZierdt\Albion\Service\ApiService;

interface UploadInterface
{
    public function __construct(ApiService $apiService);

    public function uploadIntoCsv();
}