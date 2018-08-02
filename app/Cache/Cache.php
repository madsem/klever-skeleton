<?php

namespace Klever\Cache;


use Klever\Cache\Adapters\Contracts\AdapterInterface;

class Cache implements AdapterInterface
{

    /**
     * @var $adapter \Klever\Cache\Adapters\Contracts\AdapterInterface
     */
    protected $adapter;

    function get($key)
    {
        return $this->adapter->get($key);
    }

    function put($key, $value, $minutes = null)
    {
        return $this->adapter->put($key, $value, $minutes);
    }

    function forever($key, $value)
    {
        return $this->adapter->forever($key, $value);
    }

    function remember($key, $minutes = null, callable $callback)
    {
        return $this->adapter->remember($key, $minutes, $callback);
    }

    function forget($key)
    {
        return $this->adapter->forget($key);
    }

    function forgetPattern($pattern)
    {
        return $this->adapter->forgetPattern($pattern);
    }

    function setCacheAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

}