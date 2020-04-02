<?php

use Core\Http\IMethod;
use Core\Router\Route;
use Controllers\{MainController, AuthController, ChatController, UserController, ProfileController};

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
        [IMethod::GET, IMethod::POST]
    ),
    new Route(
        '/profile',
        ProfileController::class,
        'settings',
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
