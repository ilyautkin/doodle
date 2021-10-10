<?php

require_once __DIR__ . '/www/core/config/config.inc.php';

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'development' => [
            'adapter' => $database_type,
            'host' => $database_server,
            'name' => $dbase,
            'user' => $database_user,
            'pass' => $database_password,
            'port' => '3306',
            'charset' => $database_connection_charset,
            'table_prefix' => $table_prefix,
        ],
    ],
    'version_order' => 'creation',
    'templates' => [
        'file' => '%%PHINX_CONFIG_DIR%%/db/modx_migration_template.php',
    ],
];
