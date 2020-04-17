<?php

define('APP_PATH', __DIR__);
define('ROOT_PATH', __DIR__ . '/..');

require APP_PATH . '/vendor/autoload.php';
require APP_PATH . '/Core/Autoloader.php';

use Core\Autoloader;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

use Chat\Main;
use Chat\Service;
use Chat\Store;

use Core\ServiceContainer;

Autoloader::register();

$serviceContainer = ServiceContainer::getInstance();

$serviceContainer
    ->set('env', new \Core\DotEnv(ROOT_PATH . '/.env'))
    ->set('db_context', new \Core\Db\DbContext(
        $serviceContainer->get('env')->get('DB_HOST'),
        $serviceContainer->get('env')->get('DB_PORT'),
        $serviceContainer->get('env')->get('DB_NAME'),
        $serviceContainer->get('env')->get('DB_USERNAME'),
        $serviceContainer->get('env')->get('DB_PASSWORD')
    ))
    ->set('user_repository', new \Repositories\UserRepository\UserRepository())
    ->set('chat_repository', new \Repositories\ChatRepository())
    ->set('attachment_repository', new \Repositories\AttachmentRepository())
    ->set('message_repository', new \Repositories\MessageRepository())
;

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
