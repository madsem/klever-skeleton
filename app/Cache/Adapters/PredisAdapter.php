<?php

namespace Klever\Cache\Adapters;


use Klever\Cache\Adapters\Contracts\AdapterInterface;
use Predis\Client;
use Predis\Collection\Iterator;

class PredisAdapter implements AdapterInterface
{

    /**
     * @var $client \Predis\Client
     */
    protected $client;

    protected $prefix;

    function __construct(Client $client)
    {
        $this->client = $client;
        $this->prefix = $this->client->getOptions()->prefix->getPrefix();
    }

    /**
     * @param $key
     * @return string
     */
    function get($key)
    {
        return $this->unSerializer($this->client->get($key));
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

        return $this->client->setex($key, (int)max(1, $minutes * 60), $this->serializer($value));
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    function forever($key, $value)
    {
        return $this->client->set($key, $this->serializer($value));
    }

    /**
     * Remember callback result, if result doesn't yet exist
     * it is first written to redis and then returned.
     *
     * @param $key
     * @param null $minutes
     * @param callable $callback
     * @return string
     */
    function remember($key, $minutes = null, callable $callback)
    {
        if (is_null($value = $this->get($key))) {
            $this->put($key, $value = $callback(), $minutes);
            $value = $this->get($key);
        }

        return $value;
    }

    /**
     * Send DEL command to redis
     *
     * @param string|array $keys
     * @return int
     */
    function forget($keys)
    {
        return $this->client->del($keys);
    }

    /**
     * Use Predis Iterator\Keyspace which uses SCAN under the hood
     * to iterate over matching keys, and then trigger
     * a non-blocking UNLINK (requires Redis 4.0)
     *
     * @param string $pattern = the pattern that should be used to MATCH
     * @return bool
     */
    function forgetPattern($pattern)
    {
        $keys = [
            'UNLINK'
        ];

        // append wildcard character if not present
        $pattern = stripos($pattern, '*') !== false ? $pattern : $pattern . '*';

        foreach (new Iterator\Keyspace($this->client, $this->prefix . $pattern) as $key) {
            array_push($keys, $key);
        }

        return $this->client->executeRaw($keys);
    }

    /**
     * Wrap elements in array and serialize
     * before inserting to redis
     *
     * @param mixed $value
     * @return string
     */
    private function serializer($value)
    {
        return serialize([$value]);
    }

    /**
     * Unserialize items retrieved from redis
     * and remove outer array wrapper again
     *
     * @param $value
     * @return mixed
     */
    private function unSerializer($value)
    {
        $value = unserialize($value);

        return $value[0];
    }
}