<?php
/**
 * Entry of app
 *
 * Functionality:
 * 1. Setup configuration and services
 * 2. Run app
 */

require_once __DIR__ . '/../Shared/bootstrap.php';

use Shared\Core\App;

App::setParam('routes', require_once ADMIN_PATH . '/routes.php');

/* REPOSITORIES */
App::bind('token', Client\Repositories\TokenRepository::class);
App::bind('user', Client\Repositories\UserRepository\UserRepository::class);
App::bind('image', Client\Repositories\ImageRepository::class);
App::bind('attachment', Client\Repositories\AttachmentRepository::class);
App::bind('chat', Client\Repositories\ChatRepository::class);
App::bind('message', Client\Repositories\MessageRepository::class);
App::bind('googleGeo', Client\Repositories\GoogleGeoRepository::class);
App::bind('visit', Client\Repositories\VisitRepository::class);
App::bind('goal', Client\Repositories\GoalRepository::class);
App::bind('product', Admin\Repositories\ProductRepository::class);
App::bind('counter', Admin\Repositories\CounterRepository::class);
App::bind('bill', Admin\Repositories\BillRepository::class);
App::bind('purchase', Admin\Repositories\PurchaseRepository::class);
App::bind('log', Admin\Repositories\LogRepository::class);

/* Services */
App::bind('notificationService', Client\Services\NotificationService\NotificationService::class);
App::bind('authService', Client\Services\AuthService::class);
App::bind('userService', Client\Services\UserService\UserService::class);
App::bind('imageService', Client\Services\ImageService::class);
App::bind('attachmentService', Client\Services\AttachmentService::class);
App::bind('onlineService', Client\Services\IsUserOnlineService::class);
App::bind('googleGeoService', Client\Services\GoogleGeoService\GoogleGeoService::class);
App::bind('shopService', Client\Services\ShopService::class);
App::bind('commandService', Client\Services\CommandService\Command::class);

bootstrap();
