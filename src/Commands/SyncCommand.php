<?php


namespace Revonia\BlogHub\Commands;

use Revonia\BlogHub\Hub;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SyncCommand extends Command
{

    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var Hub
     */
    private $hub;


    public function __construct(ContainerBuilder $container, Hub $hub)
    {
        $this->container = $container;
        $this->hub = $hub;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('sync')
            ->setDescription('Sync all your posts to enabled blog services');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $enabled = $this->hub->getEnabled();
        foreach ($enabled['blogServices'] as $name) {
            $service = $this->hub->resolveBlogService($this->container, $name);
        }
    }
}
