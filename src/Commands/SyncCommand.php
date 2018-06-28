<?php


namespace Revonia\BlogHub\Commands;

use Revonia\BlogHub\Hub;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncCommand extends Command
{
    protected function configure()
    {
        $this->setName('sync')
            ->setDescription('Sync all your posts to enabled blog services');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}
