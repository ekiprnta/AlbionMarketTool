<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\repositories\ResourceRepositoryUpload;
use Twig\Environment;

class ShowResourcePriceHandler
{
    public function __construct(
        private Environment $twigEnvironment,
        private ResourceRepositoryUpload $repositoryUpload,
    ) {
    }

    public function handler(): HtmlResponse
    {
        $this->repositoryUpload->uploadIntoCsv();

        $htmlContent = $this->twigEnvironment->render('test.html.twig');
        return new HtmlResponse($htmlContent);
    }
}
