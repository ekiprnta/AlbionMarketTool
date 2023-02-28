<?php

declare(strict_types=1);

namespace MZierdt\Albion\commands;

use Cron\Cron;
use Cron\Executor\Executor;
use Cron\Job\ShellJob;
use Cron\Report\CronReport;
use Cron\Resolver\ArrayResolver;
use Cron\Schedule\CrontabSchedule;
use ReflectionClass;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'cron:execute',
    description: 'Must be called every minute by Host cron. It will execute PHP crons.'
)]
class CronCommand extends Command
{
    private const CRON_COMMAND = 'cd /app && /usr/local/bin/php bin/cli.php ';

    private readonly array $cliCommands;

    public function __construct()
    {
        $this->cliCommands = [
            //            UpdateItemsCommand::class => '30 */1 * * *',
            //            UpdateMaterialsCommand::class => '30 */1 * * *',
            //            UpdateResourcesCommand::class => '30 */1 * * *',
            //            UpdateJournalsCommand::class => '30 */1 * * *',
            //            UpdateBmCraftingCommand::class => '5 */1 * * *',
            //            BmTransportITest::class => '5 */1 * * *',
            //            UpdateRefiningCommand::class => '5 */1 * * *',
            //            UpdateTransmutationCommand::class => '5 */1 * * *',
            //            UpdateNoSpecCraftingCommand::class => '5 */1 * * *',
            //            UpdateEnchantingCommand::class => '5 */1 * * *',
            //            UpdateListDataCommand::class => '5 */1 * * *',
            TestCommandDeleteLater::class => '* * * * *',
        ];
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('cron execute');

        $resolver = new ArrayResolver();

        $activeCronjobs = $this->getActiveCronjobs($resolver);

        foreach ($activeCronjobs as $cron) {
            $io->writeln($cron);
        }

        $cron = new Cron();
        $cron->setExecutor(new Executor());
        $cron->setResolver($resolver);

        /** @var CronReport $reports */
        $reports = $cron->run();

        while ($cron->isRunning()) {
            sleep(1);
        }
        foreach ($reports->getReports() as $report) {
            if ($report->isSuccessful()) {
                $output->writeln(implode(PHP_EOL, $report->getOutput()));
                continue;
            }
            $io->error(implode(PHP_EOL, $report->getOutput()));
            $output->writeln(implode(PHP_EOL, $report->getOutput()));
        }

        if ($reports->getReports() === []) {
            $io->writeln('There were no Cronjob to run at this moment.');
        } else {
            $io->writeln('Cronjobs have been executed.');
        }
        return 0;
    }

    protected function getActiveCronjobs(ArrayResolver $resolver): array
    {
        $activeCronjobs = [];
        foreach ($this->cliCommands as $cronjobClassname => $interval) {
            if (class_exists($cronjobClassname) && $interval !== null) {
                $activeCronjobs[] = $cronjobClassname . ' : ' . $interval;

                $shellJob = new ShellJob();
                $class = new ReflectionClass($cronjobClassname);

                $attributes = $class->getAttributes(AsCommand::class);
                $name = array_pop($attributes);
                $commandName = $name->getArguments()['name'];

                $shellJob->setCommand(self::CRON_COMMAND . $commandName);
                $schedule = new CrontabSchedule();
                $schedule->setPattern($interval);
                $shellJob->setSchedule($schedule);

                $resolver->addJob($shellJob);
            }
        }
        return $activeCronjobs;
    }
}
