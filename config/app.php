<?php

return [

    'debug'                             => vars('APP_DEBUG'),
    'host'                              => vars('APP_HOST', 'localhost'),

    /**
     * Application Paths
     */
    'paths'                             => [
        'views' => base_path('resources/views/'),
        'cache' => [
            'config'   => base_path('storage/cache/config/'),
            'routes'   => base_path('storage/cache/routes/'),
            'views'    => base_path('storage/cache/views/'),
            'manifest' => base_path('storage/cache/manifest/'),
        ],
    ],

    /**
     * Slim Settings
     */
    'determineRouteBeforeAppMiddleware' => true,
    'displayErrorDetails'               => vars('APP_DEBUG'),
    'routerCacheFile'                   => (vars('APP_DEBUG') ? false : base_path('storage/cache/routes/') . 'routes.php'),

    /**
     * ORM config
     */
    'connections'                       => [
        'default' => [
            'driver'    => vars('DB_DRIVER', 'mysql'),
            'host'      => vars('DB_HOST', 'localhost'),
            'port'      => vars('DB_PORT', 3306),
            'database'  => vars('DB_NAME', 'klever'),
            'username'  => vars('DB_USER', 'user'),
            'password'  => vars('DB_PASS', 'pass'),
            'charset'   => vars('DB_CHARSET', 'utf8mb4'),
            'collation' => vars('DB_COLLATION', 'utf8mb4_general_ci'),
            'prefix'    => vars('DB_PREFIX', 'klever_'),
        ],
    ],

    /**
     * AWS SDK Config
     * requires aws/aws-sdk-php
     */
    'aws'                               => [
        'key'    => vars('AWS_ACCESS_KEY_ID', null),
        'secret' => vars('AWS_SECRET_ACCESS_KEY', null),

        // SQS
        'sqs'    => [
            'region' => vars('AWS_SQS_REGION', 'us-west-1'),
            'queue'  => 'queue_name',
            'prefix' => 'prefix',
        ],

        // Another service
        // ...
    ],

    /**
     * Session Config
     * Supported handlers: 'native', 'redis'
     * TTL in minutes
     * name specifies the session cookie name
     */
    'session'                           => [
        'handler' => vars('SESSION_HANDLER', 'native'),
        'ttl'     => [
            'guests' => vars('SESSION_LIFETIME_GUESTS', 10),
            'auth'   => vars('SESSION_LIFETIME_AUTH', 120),
        ],
        'name'    => vars('SESSION_NAME', 'PHPSESSID')
    ],

    /**
     * Cookie Config
     */
    'cookie'                            => [
        'path'      => vars('COOKIE_PATH', '/'),
        'secure'    => vars('COOKIE_SECURE', false),
        'domain'    => null,
        'http_only' => vars('COOKIE_HTTP', true),
    ],

    /**
     * Cache Config
     * Supported drivers: 'array', 'redis'
     */
    'cache'                             => [
        'driver' => vars('CACHE_DRIVER', 'array'),
        'prefix' => vars('CACHE_PREFIX', 'klever_'),
    ],

    /**
     * Redis Database
     */
    'redis'                             => [
        'host'       => vars('REDIS_HOST', '127.0.0.1'),
        'port'       => vars('REDIS_PORT', 6379),
        'password'   => vars('REDIS_PASSWORD', null),
        'persistent' => 1,
    ],

    /**
     * Service Providers
     */
    'providers'                         => [
        \Klever\Providers\ConfigServiceProvider::class,
        \Klever\Providers\ConsoleServiceProvider::class,
        \Klever\Providers\MiddlewareServiceProvider::class,
        \Klever\Providers\SessionServiceProvider::class,
        \Klever\Providers\CookieServiceProvider::class,
        \Klever\Providers\FlashMessageServiceProvider::class,
        \Klever\Providers\AuthServiceProvider::class,
        \Klever\Providers\CsrfServiceProvider::class,
        \Klever\Providers\CacheServiceProvider::class,
        \Klever\Providers\DatabaseServiceProvider::class,
        \Klever\Providers\ViewServiceProvider::class,
        \Klever\Providers\PaginationServiceProvider::class,
        \Klever\Providers\ValidationServiceProvider::class,
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
            //
        ],
    ],

    /**
     * Console Commands
     */
    'commands'                          => [
        \Klever\Commands\GenerateConfigCacheCommand::class,
        \Klever\Commands\ClearCacheCommand::class,
    ],

];