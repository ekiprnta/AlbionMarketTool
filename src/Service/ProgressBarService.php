<?php

declare(strict_types=1);

namespace MZierdt\Albion\Service;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

class ProgressBarService
{
    public static function getProgressBar(OutputInterface $output, int $limit): ProgressBar
    {
        $progressBar = new ProgressBar($output, $limit);
        $progressBar->setBarWidth(50);
        ProgressBar::setFormatDefinition(
            'custom',
            ProgressBar::getFormatDefinition(ProgressBar::FORMAT_NORMAL) .
            ' -- %message%'
        );
        $progressBar->setFormat('custom');
        $progressBar->start();
        $progressBar->setMessage('Get Project Details');
        return $progressBar;
    }
}
