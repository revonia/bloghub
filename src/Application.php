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

    private $registeredCommands = [];

    /**
     * @var Env
     */
    private $env;

    /**
     * Application constructor.
     * @param ContainerBuilder $container
     * @param Dotenv $dotenv
     * @param Hub $hub
     * @throws \Exception
     */
    public function __construct(ContainerBuilder $container, Dotenv $dotenv, Hub $hub, Env $env)
    {
        $this->container = $container;
        $this->dotenv = $dotenv;
        $this->hub = $hub;
        $this->env = $env;

        parent::__construct(static::NAME, static::VERSION);

        $this->configure();

        $this->registerCommandsWithAutowire([
            SyncCommand::class,
        ]);
    }

    public function registerCommandsWithAutowire(array $commandClasses)
    {
        foreach ($commandClasses as $commandClass) {
            $this->container->autowire($commandClass)
                ->setPublic(true);
            $this->registeredCommands[] = $commandClass;
        }
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

        foreach ($this->registeredCommands as $commandClass) {
            $this->add($this->container->get($commandClass));
        }

        return parent::doRun($input, $output);
    }

    public function doBootstrap(InputInterface $input)
    {
        $hub = $this->hub;
        $app = $this;
        $container = $this->container;

        include_once __DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php';

        if ($input->hasArgument('--bootstrap')) {
            $file = $input->getArgument('--bootstrap');
            if (is_readable($file)) {
                include $file;
            } else if (is_readable($this->workingDir . DIRECTORY_SEPARATOR . $file)) {
                include $this->workingDir . DIRECTORY_SEPARATOR . $file;
            }
        }

        $hub->bootAll($this, $this->env);
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