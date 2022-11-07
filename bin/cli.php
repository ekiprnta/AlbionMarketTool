#!/usr/bin/env php
<?php

declare(strict_types=1);

use MZierdt\Albion\commands\UpdatePricesCommand;
use Symfony\Component\Console\Application;

require __DIR__ . '/../src/bootstrap.php';
$container = require __DIR__ . '/../container.php';

$app = new Application('Albion Market Tool');

$app->add($container->get(UpdatePricesCommand::class));
$app->run();
