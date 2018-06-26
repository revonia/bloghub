<?php


namespace Revonia\BlogHub;


use Revonia\BlogHub\Commands\SyncCommand;
use Symfony\Component\Console\Application as Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Dotenv\Exception\PathException;

class Application extends Console
{
    const NAME = 'BlogHub';
    const VERSION = '0.0.1';

    /**
     * @var Dotenv
     */
    private $dotEnv;

    /**
     * @var string
     */
    private $workingDir;

    /**
     * @var Hub
     */
    private $hub;

    /**
     * Application constructor.
     * @param $dotEnv
     * @param $workingDir
     */
    public function __construct($workingDir, $dotEnv, $hub)
    {
        parent::__construct(static::NAME, static::VERSION);

        $this->workingDir = $workingDir;
        $this->dotEnv = $dotEnv;
        $this->hub = $hub;

        $this->configure();

        $this->addCommands([
            new SyncCommand(),
        ]);
    }

    private function configure()
    {
        $def = $this->getDefinition();

        $def->addOptions([
            new InputOption(
                '--env',
                '-e',
                InputOption::VALUE_OPTIONAL,
                'The environment to run command.',
                ''
            ),
            new InputOption(
                '--working-dir',
                '-d',
                InputOption::VALUE_OPTIONAL,
                'If specified, use the given directory as working directory.'
            ),
            new InputOption(
                '--bootstrap',
                '-b',
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
                $this->dotEnv->load($dotEnvFile);
            } catch (PathException $e) {
            }
        } else {
            $this->dotEnv->load($dotEnvFile . ".$env");
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

        $hub = $this->hub;

        include_once __DIR__ . '/bootstrap.php';

        if ($input->hasArgument('--bootstrap')) {
            $file = $input->getArgument('--bootstrap');
            if (is_readable($file)) {
                include $file;
            } else if (is_readable($this->workingDir . '/' . $file)) {
                include $this->workingDir . '/' . $file;
            }
        }

        return parent::doRun($input, $output);
    }

    public function getHub()
    {
        return $this->hub;
    }
}