<?php

namespace Klever\Providers;

use Klever\Views\Extensions\CsrfExtension;
use Klever\Views\Extensions\LinkingExtension;
use Klever\Views\View;
use Klever\Views\ViewFactory;
use League\Container\ServiceProvider\AbstractServiceProvider;
use \Slim\Views\TwigExtension;

class ViewServiceProvider extends AbstractServiceProvider
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
        'view',
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
             ->share('view', function () {

                 $paths = [
                     config()->get('paths.views'),
                 ];

                 $settings = [
                     'cache' => config()->get('paths.cache.views'),
                     'debug' => config()->get('debug') // this also auto reloads views cache if set to true
                 ];

                 // build a new Twig Instance
                 $factory = new ViewFactory($paths, $settings);
                 $engine = $factory->getEngine();

                 // Instantiate and add Slim specific extension
                 $uri = \Slim\Http\Uri::createFromEnvironment(container()->get('environment'));
                 $engine->addExtension(
                     new TwigExtension($this->getContainer()->get('router'), $uri)
                 );

                 // TODO: find a way to lazy load extensions

                 // add app specific Twig extensions
                 $engine->addExtension(
                     new CsrfExtension($this->getContainer()->get('csrf'))
                 );

                 // register twig helpers for generating links
                 $engine->addExtension(new LinkingExtension());

                 // add \Slim\Flash messages
                 $engine->getEnvironment()->addGlobal('flash', $this->getContainer()->get('flash'));

                 // build a new View Instance
                 return new View(
                     $engine,
                     $this->getContainer()->get('request'),
                     $this->getContainer()->get('response')
                 );
             });
    }
}