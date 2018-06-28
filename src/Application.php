<?php


namespace Revonia\BlogHub;


use Revonia\BlogHub\Commands\SyncCommand;
use Symfony\Component\Console\Application as Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Dotenv\Exception\PathException;

class Application extends Console
{
    public const NAME = 'BlogHub';
    public const VERSION = '0.0.1';

    /**
     * @var Dotenv
     */
    private $dotenv;

    /**
     * @var string
     */
    private $workingDir;

    /**
     * @var Hub
     */
    private $hub;

    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * Application constructor.
     * @param ContainerBuilder $container
     * @param Dotenv $dotenv
     * @param Hub $hub
     */
    public function __construct(ContainerBuilder $container, Dotenv $dotenv, Hub $hub)
    {
        $this->container = $container;
        $this->dotenv = $dotenv;
        $this->hub = $hub;

        parent::__construct(static::NAME, static::VERSION);

        $this->configure();

        $this->addCommands([
            new SyncCommand(),
        ]);
    }

    public function setWorkingDir($workingDir)
    {
        $this->workingDir = $workingDir;
    }

    private function configure()
    {
        $def = $this->getDefinition();

        $def->addOptions([
            new InputOption(
                '--env',
                '-E',
                InputOption::VALUE_OPTIONAL,
                'The environment to run command.'
            ),
            new InputOption(
                '--working-dir',
                '-D',
                InputOption::VALUE_OPTIONAL,
                'If specified, use the given directory as working directory.'
            ),
            new InputOption(
                '--bootstrap',
                '-B',
                InputOption::VALUE_OPTIONAL,
                'If specified, bootstrap with the given php script.'
            ),
        ]);
    }

    /**
     * @param null $env
     */
    private function loadEnv($env = null)
    {
        $dotEnvFile = $this->workingDir . DIRECTORY_SEPARATOR . '.env';
        if ($env === null) {
            try {
                $this->dotenv->load($dotEnvFile);
            } catch (PathException $e) {
            }
        } else {
            $this->dotenv->load($dotEnvFile, ["$dotEnvFile.$env"]);
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Throwable
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        if ($input->hasArgument('--working-dir')) {
            $this->workingDir = $input->getArgument('--working-dir');
        }

        try {
            $this->loadEnv($input->hasArgument('--env')
                ? $input->getArgument('--env')
                : null
            );
        } catch (PathException $e) {
            $output->writeln('<comment>' . $e->getMessage() . '</comment>');
        }

        $this->doBootstrap($input);
        return parent::doRun($input, $output);
    }

    public function doBootstrap(InputInterface $input)
    {
        $hub = $this->hub;
        $app = $this;

        include_once __DIR__ . '/bootstrap.php';

        if ($input->hasArgument('--bootstrap')) {
            $file = $input->getArgument('--bootstrap');
            if (is_readable($file)) {
                include $file;
            } else if (is_readable($this->workingDir . '/' . $file)) {
                include $this->workingDir . '/' . $file;
            }
        }

        $hub->bootAll($this);
        $this->container->compile();
    }

    public function getHub()
    {
        return $this->hub;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function getWorkingDir()
    {
        return $this->workingDir;
    }
}