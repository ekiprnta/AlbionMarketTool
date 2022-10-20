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
)
{
}
}