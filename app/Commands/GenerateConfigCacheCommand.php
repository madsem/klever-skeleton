<?php

namespace Klever\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class GenerateConfigCacheCommand extends Command
{

    /**
     * @var \Slim\Container
     */
    protected $config;

    protected $cachePath;

    protected $cacheFile;

    function __construct() {
        parent::__construct();
        $this->cachePath = rtrim(config()->get('app.settings.cache_paths.config'), '/');
        $this->cacheFile = '/app.php';
    }

    /**
     * In this method setup command, description and its parameters
     */
    protected function configure()
    {
        $this->setName('cache:config');
        $this->setDescription('Builds file cache for production');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // load & merge config files and write to cache file
        file_put_contents($this->cachePath . $this->cacheFile,
            '<?php return ' . var_export(config()->config, true) . ';');

        // success
        $output->writeln('<info>Cache was built successfully!</info>');

        // return value is important when using CI
        // to fail the build when the command fails
        // 0 = success, other values = fail
        return 1;
    }

}