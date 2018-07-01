<?php

namespace Klever\Providers;

use Klever\Views\Extensions\AssetExtension;
use Klever\Views\Extensions\CsrfExtension;
use Klever\Views\View;
use League\Container\ServiceProvider\AbstractServiceProvider;
use \Slim\Views\Twig;
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

                 $view = new Twig(base_path('resources/views/'), [
                     'cache' => base_path('storage/cache/views'),
                     'debug' => config()->get('app.debug') // this also auto reloads views cache if set to true
                 ]);

                 // Instantiate and add Slim specific extension
                 $uri = $this->getContainer()->get('request')->getUri()->getBasePath();
                 $basePath = rtrim(str_ireplace('index.php', '', $uri), '/');
                 $view->addExtension(
                     new TwigExtension($this->getContainer()->get('router'), $basePath)
                 );

                 // add app specific Twig extensions
                 $view->addExtension(
                     new CsrfExtension($this->getContainer()->get('csrf'))
                 );

                 // register asset() twig helper
                 $view->addExtension(new AssetExtension());

                 // add \Slim\Flash messages
                 $view->getEnvironment()->addGlobal('flash', $this->getContainer()->get('flash'));

                 return new View(
                     $view,
                     $this->getContainer()->get('request'),
                     $this->getContainer()->get('response')
                 );
             });
    }
}