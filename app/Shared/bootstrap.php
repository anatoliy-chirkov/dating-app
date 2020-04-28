<?php

session_start();

define('ROOT_PATH', __DIR__ . '/../..');
define('APP_PATH', __DIR__ . '/..');
define('SHARED_PATH', __DIR__);
define('CLIENT_PATH', __DIR__ . '/../Client');
define('ADMIN_PATH', __DIR__ . '/../Admin');

require APP_PATH . '/vendor/autoload.php';
require SHARED_PATH . '/Core/Autoloader.php';

use Shared\Core\Autoloader;
use Shared\Core\Bootstrapper;
use Shared\Core\App;
use Shared\Core\HttpErrorHandler;

Autoloader::register();

App::setParam('envPath', ROOT_PATH . '/.env');

/* BASE CLASSES */
App::bind('env', Shared\Core\DotEnv::class);
App::bind('validator', Shared\Core\Validation\Validator::class);
App::bind('request', Shared\Core\Http\Request::class);
App::bind('routeMatcher', Shared\Core\Router\Matcher::class);
App::bind('sqlConnection', Shared\Core\Db\SQLConnection::class);

function bootstrap() {
    try {
        (new Bootstrapper())->bootstrap();
    } catch (\Exception $e) {
        HttpErrorHandler::handle($e);
    }
}
