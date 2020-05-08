<?php

use Shared\Core\Http\Request;
use Shared\Core\Router\Route;
use Client\Controllers\{MainController, AuthController, ChatController, ShopController,
    UserController, ProfileController, GeoController, VisitController};

return [
    new Route(
        '/',
        MainController::class,
        'main',
        [Request::METHOD_GET]
    ),
    new Route(
        '/search',
        UserController::class,
        'search',
        [Request::METHOD_GET]
    ),
    new Route(
        '/user/:id',
        UserController::class,
        'getOne',
        [Request::METHOD_GET]
    ),
    new Route(
        '/chats',
        ChatController::class,
        'all',
        [Request::METHOD_GET]
    ),
    new Route(
        '/user/:id/chat',
        ChatController::class,
        'concrete',
        [Request::METHOD_GET]
    ),
    new Route(
        '/chat/:chatId/get-messages',
        ChatController::class,
        'getMessages',
        [Request::METHOD_GET]
    ),
    new Route(
        '/save-message-attachment',
        ChatController::class,
        'saveAttachment',
        [Request::METHOD_POST]
    ),
    new Route(
        '/visits',
        VisitController::class,
        'see',
        [Request::METHOD_GET]
    ),
    new Route(
        '/shop',
        ShopController::class,
        'main',
        [Request::METHOD_GET]
    ),
    new Route(
        '/shop/buy/:productId',
        ShopController::class,
        'buy',
        [Request::METHOD_GET]
    ),
    new Route(
        '/shop/put-money',
        ShopController::class,
        'putMoney',
        [Request::METHOD_POST]
    ),
    new Route(
        '/payments/qiwi-callback',
        ShopController::class,
        'successPutMoneyCallback',
        [Request::METHOD_POST]
    ),
    new Route(
        '/profile',
        ProfileController::class,
        'settings',
        [Request::METHOD_GET]
    ),
    new Route(
        '/profile/edit',
        ProfileController::class,
        'edit',
        [Request::METHOD_GET, Request::METHOD_POST]
    ),
    new Route(
        '/profile/change-password',
        ProfileController::class,
        'changePassword',
        [Request::METHOD_GET, Request::METHOD_POST]
    ),
    new Route(
        '/profile/add-photo',
        ProfileController::class,
        'addPhoto',
        [Request::METHOD_POST]
    ),
    new Route(
        '/profile/choose-main-photo',
        ProfileController::class,
        'chooseMainPhoto',
        [Request::METHOD_POST]
    ),
    new Route(
        '/profile/delete-photo',
        ProfileController::class,
        'deletePhoto',
        [Request::METHOD_POST]
    ),
    new Route(
        '/geo-search',
        GeoController::class,
        'search',
        [Request::METHOD_GET]
    ),
    new Route(
        '/register',
        AuthController::class,
        'register',
        [Request::METHOD_GET, Request::METHOD_POST]
    ),
    new Route(
        '/login',
        AuthController::class,
        'login',
        [Request::METHOD_GET, Request::METHOD_POST]
    ),
    new Route(
        '/logout',
        AuthController::class,
        'logout',
        [Request::METHOD_POST]
    ),
];
