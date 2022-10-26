<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\repositories\UploadRepository;
use MZierdt\Albion\Service\UploadService;
use Twig\Environment;

class AdminHandler
{
    public function __construct(
        private Environment $twigEnvironment,
        private UploadService $uploadService,
        private UploadRepository $uploadRepository
    ) {
    }

    public function handler(): HtmlResponse
    {
        if (! empty($_POST['reloadDB'])) {
            $this->uploadRepository->emptyDb();
            $this->uploadService->uploadItemsIntoEmptyDb();
            $this->uploadService->uploadResourceIntoEmptyDb();
        }
        if (! empty($_POST['updatePrices'])) {
            $this->uploadService->uploadRefreshedPrices();
        }

        $htmlContent = $this->twigEnvironment->render('admin.html.twig');
        return new HtmlResponse($htmlContent);
    }
}
