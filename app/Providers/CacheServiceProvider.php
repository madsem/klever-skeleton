<?php

namespace Klever\Providers;


use Klever\Cache\Adapters\ArrayAdapter;
use Klever\Cache\Adapters\PredisAdapter;
use Klever\Cache\Cache;
use Klever\Config\Config;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Predis\Client;

class CacheServiceProvider extends AbstractServiceProvider
{

    /**
     * The provides array is a way to let the container
     * know that a service is provided by this service
     * provider. Every service that is registered via
     * this service provider must have an alias added
     * to this array or it will be ignored.
     *
     * @var array
     */
    protected $provides = [
        'cache',
    ];

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to, but remember, every alias registered
     * within this method must be declared in the `$provides` array.
     */
    function register()
    {
        $this->getContainer()
             ->share('cache', function () {

                 // TODO: this if / else kinda sucks, not sure how to do better atm
                 // Help wanted
                 if (config()->get('cache.driver') == 'redis') {
                     $client = new Client([
                         'scheme'   => 'tcp',
                         'host'     => config()->get('redis.host'),
                         'port'     => config()->get('redis.port'),
                         'password' => config()->get('redis.password')
                     ], [
                         'prefix' => config()->get('cache.prefix')
                     ]);

                     $adapter = new PredisAdapter($client);
                 }
                 else {
                     $adapter = new ArrayAdapter();
                 }

                 $cache = new Cache();
                 $cache->setCacheAdapter($adapter);

                 return $cache;
             });
    }
}