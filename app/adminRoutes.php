<?php

use Core\Http\IMethod;
use Core\Router\Route;
use Admin\Controllers\{MainController, AuthController, PaymentController, ProductController, UserController,
    BotController, LogController};

return [
    new Route(
        '/',
        MainController::class,
        'main',
        [IMethod::GET]
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
        [IMethod::GET]
    ),

    new Route(
        '/payments/bills',
        PaymentController::class,
        'bills',
        [IMethod::GET]
    ),
    new Route(
        '/payments/purchases',
        PaymentController::class,
        'purchases',
        [IMethod::GET]
    ),

    new Route(
        '/products/advantages',
        ProductController::class,
        'advantages',
        [IMethod::GET]
    ),
    new Route(
        '/products/pushers',
        ProductController::class,
        'pushers',
        [IMethod::GET]
    ),
    new Route(
        '/products/counters',
        ProductController::class,
        'counters',
        [IMethod::GET]
    ),
    new Route(
        '/products/create-advantage',
        ProductController::class,
        'createAdvantage',
        [IMethod::GET, IMethod::POST]
    ),
    new Route(
        '/products/create-pusher',
        ProductController::class,
        'createPusher',
        [IMethod::GET, IMethod::POST]
    ),
    new Route(
        '/products/create-counter',
        ProductController::class,
        'createCounter',
        [IMethod::GET, IMethod::POST]
    ),
    new Route(
        '/products/advantage/:id',
        ProductController::class,
        'editAdvantage',
        [IMethod::GET, IMethod::POST]
    ),
    new Route(
        '/products/pusher/:id',
        ProductController::class,
        'editPusher',
        [IMethod::GET, IMethod::POST]
    ),
    new Route(
        '/products/counter/:id',
        ProductController::class,
        'editCounter',
        [IMethod::GET, IMethod::POST]
    ),

    new Route(
        '/users',
        UserController::class,
        'all',
        [IMethod::GET]
    ),
    new Route(
        '/bots',
        BotController::class,
        'all',
        [IMethod::GET]
    ),
    new Route(
        '/logs',
        LogController::class,
        'all',
        [IMethod::GET]
    ),
];
