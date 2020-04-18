<?php

use Core\Http\IMethod;
use Core\Router\Route;
use Controllers\{MainController, AuthController, ChatController,
    UserController, ProfileController, GeoController, VisitController};

return [
    new Route(
        '/',
        MainController::class,
        'main',
        [IMethod::GET]
    ),
    new Route(
        '/search',
        UserController::class,
        'search',
        [IMethod::GET]
    ),
    new Route(
        '/user/:id',
        UserController::class,
        'getOne',
        [IMethod::GET]
    ),
    new Route(
        '/chats',
        ChatController::class,
        'all',
        [IMethod::GET]
    ),
    new Route(
        '/user/:id/chat',
        ChatController::class,
        'concrete',
        [IMethod::GET]
    ),
    new Route(
        '/chat/:chatId/get-messages',
        ChatController::class,
        'getMessages',
        [IMethod::GET]
    ),
    new Route(
        '/save-message-attachment',
        ChatController::class,
        'saveAttachment',
        [IMethod::POST]
    ),
    new Route(
        '/visits',
        VisitController::class,
        'see',
        [IMethod::GET]
    ),
    new Route(
        '/profile',
        ProfileController::class,
        'settings',
        [IMethod::GET]
    ),
    new Route(
        '/profile/edit',
        ProfileController::class,
        'edit',
        [IMethod::GET, IMethod::POST]
    ),
    new Route(
        '/profile/change-password',
        ProfileController::class,
        'changePassword',
        [IMethod::GET, IMethod::POST]
    ),
    new Route(
        '/profile/add-photo',
        ProfileController::class,
        'addPhoto',
        [IMethod::POST]
    ),
    new Route(
        '/profile/choose-main-photo',
        ProfileController::class,
        'chooseMainPhoto',
        [IMethod::POST]
    ),
    new Route(
        '/profile/delete-photo',
        ProfileController::class,
        'deletePhoto',
        [IMethod::POST]
    ),
    new Route(
        '/geo-search',
        GeoController::class,
        'search',
        [IMethod::GET]
    ),
    new Route(
        '/register',
        AuthController::class,
        'register',
        [IMethod::GET, IMethod::POST]
    ),
    new Route(
        '/login',
        AuthController::class,
        'login',
        [IMethod::GET, IMethod::POST]
    ),
    new Route(
        '/logout',
        AuthController::class,
        'logout',
        [IMethod::POST]
    ),
];
