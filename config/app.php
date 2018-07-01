<?php

return [

    'settings' => [

        'cache_paths'                       => [
            'config' => base_path('storage/cache/config/'),
            'routes' => base_path('storage/cache/routes/'),
            'views'  => base_path('storage/cache/views/'),
        ],

        /**
         * Slim Settings
         */
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails'               => env('APP_DEBUG'),
        'routerCacheFile'                   => (env('APP_DEBUG')) ? false : base_path('storage/cache/routes/') . 'routes.php',

        /**
         * ORM config
         */
        'connections'                       => [
            'default' => [
                'driver'    => env('DB_DRIVER', 'mysql'),
                'host'      => env('DB_HOST', 'localhost'),
                'database'  => env('DB_NAME', 'klever'),
                'username'  => env('DB_USER', 'user'),
                'password'  => env('DB_PASS', 'pass'),
                'charset'   => env('DB_CHARSET', 'utf8'),
                'collation' => env('DB_COLLATION', 'utf8_unicode_ci'),
                'prefix'    => env('DB_PREFIX', ''),
            ],
        ],

        /**
         * Session Config
         * Supported handlers: 'native', 'redis'
         * Lifetime in minutes
         * name specifies the session cookie name
         */
        'session'                           => [
            'handler' => env('SESSION_HANDLER', 'native'),
            'ttl'     => env('SESSION_LIFETIME', 120),
            'name'    => env('SESSION_NAME', 'klever_')
        ],

        /**
         * Cookie Config
         */
        'cookie'                            => [
            'path'      => env('COOKIE_PATH', '/'),
            'secure'    => env('COOKIE_SECURE', false),
            'domain'    => request()->getUri()->getHost(),
            'http_only' => env('COOKIE_HTTP', true),
        ],

        /**
         * Cache Config
         * Supported drivers: 'array', 'redis'
         */
        'cache'                             => [
            'driver' => env('CACHE_DRIVER', 'array'),
            'prefix' => env('CACHE_PREFIX', 'klever_'),
        ],

        /**
         * Redis Database
         */
        'redis'                             => [
            'host'       => env('REDIS_HOST', '127.0.0.1'),
            'port'       => env('REDIS_PORT', 6379),
            'password'   => env('REDIS_PASSWORD', null),
            'persistent' => 1,
        ],

        /**
         * Service Providers
         */
        'providers'                         => [
            \Klever\Providers\ConsoleServiceProvider::class,
            \Klever\Providers\SessionServiceProvider::class,
            \Klever\Providers\CookieServiceProvider::class,
            \Klever\Providers\FlashMessageServiceProvider::class,
            \Klever\Providers\AuthServiceProvider::class,
            \Klever\Providers\CsrfServiceProvider::class,
            \Klever\Providers\CacheServiceProvider::class,
            \Klever\Providers\DatabaseServiceProvider::class,
            \Klever\Providers\ViewServiceProvider::class,

        ],

        /**
         * Route Groups Middleware
         *
         * These Middlewares run globally for each route group.
         * Other middlewares should be attached in routes file.
         */
        'middleware'                        => [
            'web' => [
                \Klever\Middleware\SessionMiddleWare::class,
            ],
            'api' => [

            ],
        ],

        /**
         * Console Commands
         */
        'commands'                          => [
            \Klever\Commands\GenerateConfigCacheCommand::class,
            \Klever\Commands\ClearCacheCommand::class,
        ],

    ],

];