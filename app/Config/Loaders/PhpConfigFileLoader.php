<?php

namespace Klever\Config\Loaders;

use \Klever\Config\Contracts\LoaderInterface;

class PhpConfigFileLoader implements LoaderInterface
{

    private $path;
    protected $cacheFile;

    function __construct(string $path, string $cacheFile)
    {
        $this->path = rtrim($path, '/');
        $this->cacheFile = $cacheFile;
    }

    /**
     * Parse PHP config files from given path
     * and add to array.
     *
     * @return array
     */
    function parse(): array
    {
        // try to load files from cache
        $files = $this->isCached();

        if ( ! $files) {
            $iterator = new \GlobIterator($this->path . '/*.php', \FilesystemIterator::KEY_AS_FILENAME);

            if ($iterator->count()) {
                foreach ($iterator as $file) {
                    // get filename without .ext
                    $key = $file->getBasename('.' . $file->getExtension());

                    try {
                        $files[$key] = require_once $file->getPathname();
                    } catch (\Exception $e) {
                        //
                    }
                }
            }
        }

        return $files;
    }

    /**
     * Check if cached config exists
     *
     * @return bool|array
     */
    function isCached()
    {
        if (file_exists($this->cacheFile)) {
            return require_once $this->cacheFile;
        }

        return false;
    }
}