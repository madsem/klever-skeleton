<?php

namespace Klever\Providers;

use Klever\Config\Config;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Symfony\Component\Console\Application;

class ConsoleServiceProvider extends AbstractServiceProvider
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
        'console',
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
             ->add('console', function () {

                 // register console commands
                 $commands = [];
                 foreach (config()->get('app.settings.commands') as $command) {

                     // auto-wire commands with arguments
                     $newCommand = $this->getContainer()->get($command);

                     array_push($commands, $newCommand);
                 }

                 $application = new Application();
                 $application->addCommands($commands);

                 return $application;
             });
    }
}