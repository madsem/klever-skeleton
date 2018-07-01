<?php

namespace Klever\Config;

class Config
{

    /**
     * @var array \Klever\Config\Loaders\Loader
     */
    public $config;

    private $cache = [];

    function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Get / set value from array cache
     * for config values
     *
     * @param $key
     * @param null $default
     * @return mixed
     */
    function get($key, $default = null)
    {
        if ($this->existsInCache($key)) {
            return $this->fromCache($key);
        }

        return $this->addToCache(
            $key,
            $this->extractFromConfig($key) ?? $default
        );
    }

    /**
     * Extract last part from dot notated string
     * from config array
     *
     * @param $key
     * @return array|mixed
     */
    protected function extractFromConfig($key)
    {
        $filtered = $this->config;

        foreach (explode('.', $key) as $segment) {
            if ($this->exists($filtered, $segment)) {
                $filtered = $filtered[$segment];
                continue;
            }
        }

        return $filtered;
    }

    /**
     * Check if key exists in config cache
     *
     * @param $key
     * @return bool
     */
    protected function existsInCache($key)
    {
        return isset($this->cache[$key]);
    }

    /**
     * Retrieve value by key from cache
     *
     * @param $key
     * @return mixed
     */
    protected function fromCache($key)
    {
        return $this->cache[$key];
    }

    /**
     * Add value to cache
     *
     * @param $key
     * @param $value
     * @return mixed
     */
    protected function addToCache($key, $value)
    {
        $this->cache[$key] = $value;

        return $value;
    }

    /**
     * Check if key exists in config
     *
     * @param array $config
     * @param $key
     * @return bool
     */
    protected function exists(array $config, $key)
    {
        return array_key_exists($key, $config);
    }
}