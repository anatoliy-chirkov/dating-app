<?php

define('ROOT_PATH', __DIR__ . '/..');
define('APP_PATH', __DIR__ . '/..');
define('SHARED_PATH', __DIR__ . '/../Shared');

require APP_PATH . '/vendor/autoload.php';
require SHARED_PATH . '/Core/Autoloader.php';

use Shared\Core\Autoloader;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use WebSocket\Core\Main;
use WebSocket\Core\Service;
use WebSocket\Core\Store;
use Shared\Core\App;

Autoloader::register();

App::setParam('envPath', ROOT_PATH . '/.env');

/* BASE CLASSES */
App::bind('env', Shared\Core\DotEnv::class);
App::bind('validator', Shared\Core\Validation\Validator::class);
App::bind('sqlConnection', Shared\Core\Db\SQLConnection::class);

/* REPOSITORIES */
App::bind('user', Client\Repositories\UserRepository\UserRepository::class);
App::bind('attachment', Client\Repositories\AttachmentRepository::class);
App::bind('chat', Client\Repositories\ChatRepository::class);
App::bind('message', Client\Repositories\MessageRepository::class);
App::bind('counter', Admin\Repositories\CounterRepository::class);

$store       = new Store();
$service     = new Service($store);
$mainHandler = new Main($service);

$server = IoServer::factory(
    new HttpServer(
        new WsServer($mainHandler)
    ),
    8080
);

$server->run();
