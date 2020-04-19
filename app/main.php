<?php
/**
 * Entry of app
 *
 * Functionality:
 * 1. Setup configuration and services
 * 2. Run app
 */

session_start();

define('APP_PATH', __DIR__);
define('ROOT_PATH', __DIR__ . '/..');

require APP_PATH . '/vendor/autoload.php';
require APP_PATH . '/Core/Autoloader.php';

use Core\Autoloader;
use Core\Bootstrapper;
use Core\ServiceContainer;

Autoloader::register();

$serviceContainer = ServiceContainer::getInstance();

$serviceContainer
    ->set('users_img_dir', ['server' => APP_PATH . '/FrontendAssets/img/user', 'client' => '/img/user'])
    ->set('chats_img_dir', ['server' => APP_PATH . '/FrontendAssets/img/chat', 'client' => '/img/chat'])
    ->set('env', new \Core\DotEnv(ROOT_PATH . '/.env'))
    ->set('routes', require_once APP_PATH . '/routes.php')
    ->set('request', new \Core\Http\Request())
    ->set('route_matcher', new \Core\Router\Matcher())
    ->set('db_context', new \Core\Db\DbContext(
        $serviceContainer->get('env')->get('DB_HOST'),
        $serviceContainer->get('env')->get('DB_PORT'),
        $serviceContainer->get('env')->get('DB_NAME'),
        $serviceContainer->get('env')->get('DB_USERNAME'),
        $serviceContainer->get('env')->get('DB_PASSWORD')
    ))
    ->set('validator', new \Core\Validation\Validator())
    ->set('token_repository', new \Repositories\TokenRepository())
    ->set('notification_service', new \Services\NotificationService\NotificationService())

    // Repo
    ->set('user_repository', new \Repositories\UserRepository\UserRepository())
    ->set('image_repository', new \Repositories\ImageRepository())
    ->set('attachment_repository', new \Repositories\AttachmentRepository())
    ->set('chat_repository', new \Repositories\ChatRepository())
    ->set('message_repository', new \Repositories\MessageRepository())
    ->set('google_geo_repository', new \Repositories\GoogleGeoRepository())
    ->set('visit_repository', new \Repositories\VisitRepository())
    ->set('goal_repository', new \Repositories\GoalRepository())

    // Services
    ->set('auth_service', new \Services\AuthService())
    ->set('user_service', new \Services\UserService\UserService())
    ->set('image_service', new \Services\ImageService())
    ->set('attachment_service', new \Services\AttachmentService())
    ->set('is_user_online_service', new \Services\IsUserOnlineService())
    ->set('google_geo_service', new \Services\GoogleGeoService\GoogleGeoService())
;

try {
    (new Bootstrapper())->bootstrap();
} catch (\Exception $e) {
    header("HTTP/1.0 {$e->getCode()}");

    echo <<<HTML
<html>
    <header>
        <title>{$e->getMessage()}</title>
    </header>
    <body style="text-align: center;margin-top: 46px;">
        <h1>{$e->getCode()}</h1>
        <div>{$e->getMessage()}</div>
    </body>
</html>
HTML;
}
