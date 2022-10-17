<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use _PHPStan_acbb55bae\Nette\Utils\JsonException;
use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\HttpClient;
use MZierdt\Albion\repositories\HunterUploadRepository;
use MZierdt\Albion\repositories\MageUploadRepository;
use MZierdt\Albion\repositories\ResourceUploadRepository;
use MZierdt\Albion\repositories\WarriorUploadRepository;
use MZierdt\Albion\Service\ApiService;
use MZierdt\Albion\Service\NameDataService;
use Twig\Environment;

class ShowResourcePriceHandler
{
    public function __construct(
        private Environment $twigEnvironment,
        private ResourceUploadRepository $repositoryUpload,
        private WarriorUploadRepository $warriorUpload,
        private MageUploadRepository $mageUpload,
        private HunterUploadRepository $hunterUpload,
    ) {
    }

    public function handler(): HtmlResponse
    {
//        $this->repositoryUpload->uploadIntoCsv();
        $this->warriorUpload->uploadIntoCsv();
//        $this->mageUpload->uploadIntoCsv();
//        $this->hunterUpload->uploadIntoCsv();

        $htmlContent = $this->twigEnvironment->render('test.html.twig');
        return new HtmlResponse($htmlContent);
    }
}
