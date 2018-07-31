<?php

namespace Klever\Cache\Adapters;


use Klever\Cache\Adapters\Contracts\AdapterInterface;

class ArrayAdapter implements AdapterInterface
{

    private $cache = [];

    /**
     * @param $key
     * @return mixed
     */
    function get($key)
    {
        if ($this->existsInCache($key)) {
            return $this->fromCache($key);
        }

        return false;
    }

    /**
     * @param $key
     * @param $value
     * @param null $minutes
     * @return mixed
     */
    function put($key, $value, $minutes = null)
    {
        return $this->addToCache($key, $value);
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    function forever($key, $value)
    {
        return $this->addToCache($key, $value);
    }

    /**
     * @param $key
     * @param null $minutes
     * @param callable $callback
     * @return mixed
     */
    function remember($key, $minutes = null, callable $callback)
    {
        return $this->addToCache($key, $callback());
    }

    /**
     * @param $key
     * @return bool
     */
    function forget($key)
    {
        unset($this->cache[$key]);

        return true;
    }

    /**
     * Just an empty method because
     * array cache is not persistent anyways
     *
     * @param string $pattern
     * @return bool
     */
    function forgetPattern($pattern)
    {
        return true;
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    private function addToCache($key, $value)
    {
        return $this->cache[$key] = $value;
    }

    /**
     * @param $key
     * @return bool
     */
    private function existsInCache($key)
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
}