<?php

namespace Klever\Providers;


use Klever\Middleware\ForceSslMiddleWare;
use League\Container\ServiceProvider\AbstractServiceProvider;

class MiddlewareServiceProvider extends AbstractServiceProvider
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
        'force-ssl',
    ];

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to, but remember, every alias registered
     * within this method must be declared in the `$provides` array.
     */
    function register()
    {

        // register force ssl middleware
        $this->getContainer()
             ->share('force-ssl', function () {
                 return new ForceSslMiddleWare();
             });

    }
}