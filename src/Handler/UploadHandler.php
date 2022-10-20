<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use MZierdt\Albion\repositories\ResourceUploadRepository;
use MZierdt\Albion\Service\ApiService;

class UploadHandler
{
    public function __construct(
        private ApiService $apiService,
        private ResourceUploadRepository $resourceUploadRepository,
    ) {
    }

    public function uploadResourceIntoDb()
    {
        $metalBarArray = $this->apiService->getResource('metalBar');
        $planksArray = $this->apiService->getResource('planks');
        $clothArray = $this->apiService->getResource('cloth');
        $leatherArray = $this->apiService->getResource('leather');

        dd($metalBarArray);
    }

    protected function adjustResourceArray(array $resourceArray)
    {

    }
}