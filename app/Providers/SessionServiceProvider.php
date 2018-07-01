<?php

namespace Klever\Providers;


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
        $this->getContainer()
             ->share('session', function () {

                 // TODO: this if / else kinda sucks, not sure how to do better atm
                 // Help wanted
                 if (config()->get('app.settings.session.handler') == 'redis') {
                     $client = new Client([
                         'scheme'   => 'tcp',
                         'host'     => config()->get('app.settings.redis.host'),
                         'port'     => config()->get('app.settings.redis.port'),
                         'password' => config()->get('app.settings.redis.password')
                     ], [
                         'prefix' => config()->get('app.settings.cache.prefix')
                     ]);

                     $handler = new PredisSessionHandler($client, [
                         'ttl' => config()->get('app.settings.session.ttl')
                     ]);
                 }
                 else {
                     // fall back to native session handler
                     $handler = new \SessionHandler();
                 }

                 return (new Session($handler, config()->get('app.settings.session.name')));
             });
    }
}