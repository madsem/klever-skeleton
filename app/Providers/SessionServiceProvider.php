<?php

namespace Klever\Providers;


use Klever\Session\Handlers\NativeSessionHandler;
use Klever\Session\Handlers\PredisSessionHandler;
use Klever\Storage\Session;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Predis\Client;

class SessionServiceProvider extends AbstractServiceProvider
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
        'session-handler',
        'session',
    ];

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to, but remember, every alias registered
     * within this method must be declared in the `$provides` array.
     */
    function register()
    {

        // register session handler
        $this->getContainer()
             ->share('session-handler', function () {

                 $settings = array_merge(
                     config()->get('session'),
                     config()->get('cookie')
                 );

                 if (config()->get('session.handler') == 'redis') {
                     $client = new Client([
                         'scheme'   => 'tcp',
                         'host'     => config()->get('redis.host'),
                         'port'     => config()->get('redis.port'),
                         'password' => config()->get('redis.password')
                     ], [
                         'prefix' => config()->get('cache.prefix')
                     ]);

                     $handler = new PredisSessionHandler($client, $settings);
                 }
                 else {
                     // fall back to native session handler
                     $handler = new NativeSessionHandler($settings);
                 }

                 return $handler;
             });

        // register session container
        $this->getContainer()
             ->share('session', function () {

                 $handler = container()->get('session-handler');

                 return (new Session($handler));
             });
    }
}