#!/usr/bin/env php
<?php

declare(strict_types=1);

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;

require 'vendor/autoload.php';

$container = require __DIR__ . '/../container.php';

$entityManager = $container->get(\Doctrine\ORM\EntityManager::class);

$commands = [];

ConsoleRunner::run(new SingleManagerProvider($entityManager), $commands);
