<?php
require_once('vendor/autoload.php');

// Load dotenv ke $_SERVER
Dotenv\Dotenv::createMutable(__DIR__)->safeLoad();

// Sinkronkan variabel environment dari getenv(), $_ENV, dan $_SERVER
foreach (getenv() as $key => $val) {
    if (!isset($_ENV[$key])) {
        $_ENV[$key] = $val;
    }
    if (!isset($_SERVER[$key])) {
        $_SERVER[$key] = $val;
    }
}
foreach ($_ENV as $key => $val) {
    if (!isset($_SERVER[$key]) && is_string($val)) {
        $_SERVER[$key] = $val;
    }
}
foreach ($_SERVER as $key => $val) {
    if (!isset($_ENV[$key]) && is_string($val)) {
        $_ENV[$key] = $val;
    }
}

return
    [
        'paths' => [
            'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
            'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
        ],
        'environments' => [
            'default_migration_table' => 'phinxlog',
            'default_environment' => 'development',
            'production' => [
                'adapter' => 'mysql',
                'host' => 'localhost',
                'name' => 'production_db',
                'user' => 'root',
                'pass' => '',
                'port' => '3306',
                'charset' => 'utf8',
            ],
            'development' => [
                'adapter' => 'mysql',
                'host' => $_ENV['DB_HOST'],
                'name' => $_ENV['DB_NAME'],
                'user' => $_ENV['DB_USER'],
                'pass' => $_ENV['DB_PASS'],
                'port' => '3306',
                'charset' => 'utf8',
            ],
        ],
        'version_order' => 'creation'
    ];
