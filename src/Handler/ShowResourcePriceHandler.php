<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use _PHPStan_acbb55bae\Nette\Utils\JsonException;
use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\repositories\ResourceUploadRepository;
use MZierdt\Albion\Service\JsonService;
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
        $x = new JsonService();
        $x->getNameDataArray();

        $this->repositoryUpload->uploadIntoCsv();

        $htmlContent = $this->twigEnvironment->render('test.html.twig');
        return new HtmlResponse($htmlContent);
    }
}
