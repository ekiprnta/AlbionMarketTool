<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\repositories\ResourceUploadRepository;
use Twig\Environment;

class ShowResourcePriceHandler
{
    public function __construct(
        private Environment $twigEnvironment,
        private ResourceUploadRepository $repositoryUpload,
    ) {
    }

    public function handler(): HtmlResponse
    {
        $this->repositoryUpload->uploadIntoCsv();

        $htmlContent = $this->twigEnvironment->render('test.html.twig');
        return new HtmlResponse($htmlContent);
    }
}
