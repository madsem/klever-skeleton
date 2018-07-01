<?php

namespace Klever\Providers;

use Klever\Config\Config;
use Klever\Config\Loaders\PhpConfigFileLoader;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;

class ConfigServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{

    /**
     * @var array
     */
    protected $provides = [
        'config',
    ];

    /**
     * In much the same way, this method has access to the container
     * itself and can interact with it however you wish, the difference
     * is that the boot method is invoked as soon as you register
     * the service provider with the container meaning that everything
     * in this method is eagerly loaded.
     *
     * If you wish to apply inflectors or register further service providers
     * from this one, it must be from a bootable service provider like
     * this one, otherwise they will be ignored.
     */
    function boot()
    {
        $this->getContainer()
             ->share('config', function () {
                 $config = new PhpConfigFileLoader(
                     base_path('config'),
                     base_path('storage/cache/config/app.php')
                 );

                 return (new Config($config->parse()));
             });
    }

    /**
     * {@inheritdoc}
     */
    function register()
    {
        // ...
    }
}