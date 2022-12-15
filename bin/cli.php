#!/usr/bin/env php
<?php

declare(strict_types=1);

use MZierdt\Albion\commands\DeleteDataCommand;
use MZierdt\Albion\commands\UpdateItemsCommand;
use MZierdt\Albion\commands\UpdateJournalsCommand;
use MZierdt\Albion\commands\UpdateRawResourcesCommand;
use MZierdt\Albion\commands\UpdateResourcesCommand;
use Symfony\Component\Console\Application;

require __DIR__ . '/../src/bootstrap.php';
$container = require __DIR__ . '/../container.php';

$app = new Application('Albion Market Tool');

$app->add($container->get(UpdateItemsCommand::class));
$app->add($container->get(UpdateJournalsCommand::class));
$app->add($container->get(UpdateResourcesCommand::class));
$app->add($container->get(UpdateRawResourcesCommand::class));
$app->add($container->get(DeleteDataCommand::class));
$app->run();
