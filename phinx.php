<?php

require 'bootstrap/app.php';

return [

    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/database/migrations',
    ],

    'migration_base_class' => 'Klever\App\Database\Migrations\Migration',

    'templates' => [
        'file' => '%%PHINX_CONFIG_DIR%%/app/App/Database/Migrations/Migration.stub',
    ],

    'environments' => [
        'default_migration_table' => 'phinx_migration',
        'default'                 => [
            'adapter'      => config()->get('connections.default.driver'),
            'host'         => config()->get('connections.default.host'),
            'port'         => config()->get('connections.default.port'),
            'name'         => config()->get('connections.default.database'),
            'user'         => config()->get('connections.default.username'),
            'pass'         => config()->get('connections.default.password'),
            'collation'    => config()->get('connections.default.collation'),
            'charset'      => config()->get('connections.default.charset'),
            'table_prefix' => config()->get('connections.default.prefix'),
        ],
    ],

];