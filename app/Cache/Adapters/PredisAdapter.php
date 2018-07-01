<?php

namespace Klever\Cache\Adapters;


use Klever\Cache\Adapters\Contracts\AdapterInterface;
use Predis\Client;

class PredisAdapter implements AdapterInterface
{

    /**
     * @var $client \Predis\Client
     */
    protected $client;

    function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param $key
     * @return string
     */
    function get($key)
    {
        return $this->client->get($key);
    }

    /**
     * @param $key
     * @param $value
     * @param null $minutes
     * @return int|mixed
     */
    function put($key, $value, $minutes = null)
    {
        if ($minutes === null) {
            return $this->forever($key, $value);
        }

        return $this->client->setex($key, (int)max(1, $minutes * 60), $value);
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    function forever($key, $value)
    {
        return $this->client->set($key, $value);
    }

    /**
     *
     * @param $key
     * @param null $minutes
     * @param callable $callback
     * @return string
     */
    function remember($key, $minutes = null, callable $callback)
    {
        if ( ! is_null($value = $this->get($key))) {
            return $value;
        }

        $this->put($key, $value = $callback(), $minutes);

        return $value;
    }

    /**
     * Send DEL command to redis
     *
     * @param string|array $key
     * @return int
     */
    function forget($key)
    {
        return $this->client->del($key);
    }
}