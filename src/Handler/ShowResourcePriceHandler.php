<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\repositories\ResourceUploadRepository;
use MZierdt\Albion\repositories\UploadCsvRepository;
use Twig\Environment;

class ShowResourcePriceHandler
{
    public function __construct(
        private Environment $twigEnvironment,
    ) {
    }

    public function handler(): HtmlResponse
    {
        $htmlContent = $this->twigEnvironment->render('test.html.twig');
        return new HtmlResponse($htmlContent);
    }
}
