<?php

declare(strict_types=1);

namespace MZierdt\Albion\commands;

use MZierdt\Albion\Entity\ItemEntity;
use MZierdt\Albion\repositories\ItemRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'debug:test', description: '')]
class TestCommandDeleteLater extends Command
{
    public function __construct(private readonly ItemRepository $itemRepository)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $this->generateRandomString();
        $entity = (new ItemEntity())
            ->setTier(99)
            ->setName($name)
            ->setWeaponGroup('testWeapon')
            ->setCity('TestCity');

        $this->itemRepository->createOrUpdate($entity);

        $output->writeln('This is a test pleas stay calm (IGJMK)');
        return self::SUCCESS;
    }

    private function generateRandomString(): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';
        for ($i = 0; $i < 10; $i++) {
            $string .= $characters[random_int(0, 61)];
        }
        return $string;
    }
}