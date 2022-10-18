<?php

declare(strict_types=1);

namespace MZierdt\Albion\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use MZierdt\Albion\repositories\ItemRepository;
use MZierdt\Albion\repositories\ResourceRepository;
use MZierdt\Albion\repositories\ResourceUploadRepository;
use MZierdt\Albion\repositories\UploadCsvRepository;
use Twig\Environment;

class ShowResourcePriceHandler
{
    public function __construct(
        private Environment $twigEnvironment,
        private UploadCsvRepository $uploadCsvRepository,
        private ResourceUploadRepository $repositoryUpload,

    ) {
    }

    public function handler(): HtmlResponse
    {
//        $this->uploadCsvRepository->fillItemsCsvFiles();
//        $this->repositoryUpload->uploadIntoCsv();

        $bla = new ItemRepository();
        $bla->getItemsAsItemEntityFromCity('Martlock');


        $htmlContent = $this->twigEnvironment->render('test.html.twig');
        return new HtmlResponse($htmlContent);
    }
}
