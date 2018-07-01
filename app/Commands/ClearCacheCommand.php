<?php

namespace Klever\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ClearCacheCommand extends Command
{

    /**
     * In this method setup command, description and its parameters
     */
    protected function configure()
    {
        $this->setName('cache:clear');
        $this->setDescription('Clears all caches');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->removeDirectoryContents(
            config()->get('app.settings.cache_paths')
        );

        // success
        $output->writeln('<info>Cache was cleared successfully!</info>');

        // return value is important when using CI
        // to fail the build when the command fails
        // 0 = success, other values = fail
        return 1;
    }

    /**
     * recursively remove files and directories
     * exclude gitkeep files
     *
     * @param array $paths
     * @return bool
     */
    function removeDirectoryContents(array $paths)
    {
        foreach ($paths as $path) {
            $di = new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS);
            $ri = new \RecursiveIteratorIterator($di, \RecursiveIteratorIterator::CHILD_FIRST);
            foreach ($ri as $file) {
                if ('gitkeep' !== $file->getExtension()) {
                    $file->isDir() ? rmdir($file) : unlink($file);
                }
            }
        }

        return true;
    }

}