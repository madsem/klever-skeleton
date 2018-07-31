<?php

namespace Klever\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use \Slim\Csrf\Guard;

class CsrfServiceProvider extends AbstractServiceProvider
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
        'csrf',
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
             ->share('csrf', function () {

                 $csrf = new Guard();

                 // set to true if only single-user app
                 // to make repeated ajax calls work with csrf failure.
                 $csrf->setPersistentTokenMode(true);

                 return $csrf;
             });
    }
}