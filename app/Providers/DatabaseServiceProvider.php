<?php

namespace Klever\Providers;


use Illuminate\Database\Capsule\Manager;
use League\Container\ServiceProvider\AbstractServiceProvider;

class DatabaseServiceProvider extends AbstractServiceProvider
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
        'db',
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
             ->share('db', function () {

                 $capsule = new Manager();
                 // configure connections
                 foreach (config()->get('connections') as $name => $connection) {
                     $capsule->addConnection($connection, $name);
                 }

                 $capsule->setAsGlobal();
                 $capsule->bootEloquent();

                 return $capsule;
             });
    }
}