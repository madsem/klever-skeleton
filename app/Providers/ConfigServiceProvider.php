<?php

namespace Klever\Providers;

use Klever\Config\Config;
use League\Container\ServiceProvider\AbstractServiceProvider;

class ConfigServiceProvider extends AbstractServiceProvider
{

    /**
     * @var array
     */
    protected $provides = [
        'config',
    ];

    function register()
    {
        $this->getContainer()
             ->share('config', function () {
                 $config = container()->get('settings');

                 return (new Config($config));
             });
    }

    function boot()
    {
        // ...
    }
}