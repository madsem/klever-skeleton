<?php

namespace Klever\App;


use Dotenv\Dotenv;
use Dotenv\Exception\InvalidFileException;
use Dotenv\Exception\InvalidPathException;
use Klever\Providers\AppServiceProvider;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Slim\App;

class Wrapper
{

    private static $app = null;

    /**
     * Return Singleton
     *
     * @return \Slim\App
     */
    static function getInstance(): App
    {
        if (null === self::$app) {
            self::$app = self::makeInstance();

            // bootstrap $app
            self::bootServiceProviders();
        }

        return self::$app;
    }

    /**
     * @return \Slim\App
     */
    private static function makeInstance(): App
    {
        // load environment variables
        if ( ! file_exists(base_path('storage/cache/config/app.php'))) {
            try {
                $dotEnv = new Dotenv(base_path());
                $dotEnv->load();
            } catch (InvalidPathException $e) {
                //
            } catch (InvalidFileException $e) {
                die('The environment file is invalid: ' . $e->getMessage());
            }
        }

        // use the PHP League Container
        $container = new Container();
        $container->delegate(new ReflectionContainer());
        $container->addServiceProvider(new AppServiceProvider());

        // instantiate Slim
        $app = new App($container);

        return $app;
    }

    /**
     * Bootstrap App after initial instantiation
     * That way global helper methods, and the app container
     * are available everywhere and can even be used inside of the app config.
     */
    private static function bootServiceProviders()
    {
        // load all other service providers
        foreach (container()->get('settings')['providers'] as $provider) {
            container()->addServiceProvider(new $provider);
        }

        // make db connection available globally
        container()->get('db');
    }
}