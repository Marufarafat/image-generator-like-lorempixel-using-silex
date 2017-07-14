<?php

define("INC_ROOT", __DIR__);

require_once INC_ROOT . "/vendor/autoload.php";

$app = new Silex\Application([
    'debug' => true
]);

require_once INC_ROOT . "/routes/routes.php";
//require_once INC_ROOT . "/App/Providers/ImageServiceProvider.php";

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_mysql',
        'dbname'    => 'loremimage',
        'host'  => 'localhost',
        'user'  => 'root',
        'password'  => 'root',
        'password'  => 'root',
        'charset'   => 'utf8mb4',
    ),
));

$app->register(new App\Providers\ImageServiceProvider);

$app->register(new Moust\Silex\Provider\CacheServiceProvider, [
    'caches.options' => [
        'filesystem' => [
            'driver' =>     'file',
            'cache_dir' =>   INC_ROOT.'/caches/img',
            ]
    ],
]);