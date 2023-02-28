<?php

declare(strict_types=1);

namespace MZierdt\Albion\commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'debug:test', description: '')]
class TestCommandDeleteLater extends Command
{

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('This is a test pleas stay calm (IGJMK)');
        return self::SUCCESS;
    }
}