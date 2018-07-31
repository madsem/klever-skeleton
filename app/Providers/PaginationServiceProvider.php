<?php

namespace Klever\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use \Illuminate\Pagination\LengthAwarePaginator;
use \Illuminate\Pagination\Paginator;
use \Klever\Views\ViewFactory;

class PaginationServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
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
    protected $provides = [];

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to, but remember, every alias registered
     * within this method must be declared in the `$provides` array.
     */
    function boot()
    {

        LengthAwarePaginator::viewFactoryResolver(function () {
            $paths = [
                config()->get('paths.views')
            ];

            $settings = [
                'cache' => config()->get('paths.cache.views'),
                'debug' => config()->get('app.debug') // this also auto reloads views cache if set to true
            ];

            return new ViewFactory($paths, $settings);
        });

        Paginator::currentPathResolver(function () {
            return container()->get('request')->geturi()->getPath();
        });

        Paginator::currentPageResolver(function () {
            return container()->get('request')->getQueryParam('page', 1);
        });

        LengthAwarePaginator::defaultView('templates/partials/pagination.twig');

    }

    function register()
    {
        //
    }
}