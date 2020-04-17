<?php

require __DIR__ . '/../app/Core/DotEnv.php';
$env = new Core\DotEnv(__DIR__ . '/..' . '/.env');

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/Database/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/Database/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'development' => [
            'adapter' => 'mysql',
            'host' => $env->get('DB_HOST'),
            'name' => $env->get('DB_NAME'),
            'user' => $env->get('DB_USERNAME'),
            'pass' => $env->get('DB_PASSWORD'),
            'port' => $env->get('DB_PORT'),
            'charset' => 'utf8',
        ],
    ],
    'version_order' => 'creation'
];
