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
            $files = [];
            $iterator = new \GlobIterator($this->path . '/*.php', \FilesystemIterator::KEY_AS_FILENAME);

            if ($iterator->count()) {
                foreach ($iterator as $file) {

                    try {
                        $file = require_once $file->getPathname();
                        array_push($files, $file);
                    } catch (\Exception $e) {
                        //
                    }
                }
            }

            // merge first level of array so we have one big settings array
            $files =  call_user_func_array('array_merge', $files);
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
        // memoize results
        static $cache = null;

        if (is_null($cache)) {
            if (file_exists($this->cacheFile)) {
                $cache = require_once $this->cacheFile;
            }
            else {
                $cache = false;
            }
        }

        return $cache;
    }
}