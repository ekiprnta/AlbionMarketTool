<?php

namespace MZierdt\Albion\commands;

use MZierdt\Albion\repositories\DeleteDataRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteDataCommand extends Command
{
    public function __construct(
        private DeleteDataRepository $deleteDataRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $message = 'successfully emptied the tables';

        $this->deleteDataRepository->deleteDataFromTable('items');
        $this->deleteDataRepository->deleteDataFromTable('resource');
        $this->deleteDataRepository->deleteDataFromTable('journals');

        $output->writeln(PHP_EOL . $message);
        return self::SUCCESS;
    }

    protected function configure()
    {
        $this->setName('clear:table');
        $this->setDescription('Empty the Table');
    }
}
