<?php

use Shared\Core\Router\Route;
use Shared\Core\Http\Request;
use Admin\Controllers\{MainController, AuthController, PaymentController, ProductController, UserController,
    BotController, LogController, CounterController, ProductGroupController};

return [
    new Route(
        '/',
        MainController::class,
        'main',
        [Request::METHOD_GET]
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
        [Request::METHOD_GET]
    ),

    new Route(
        '/payments/bills',
        PaymentController::class,
        'bills',
        [Request::METHOD_GET]
    ),
    new Route(
        '/payments/bills/search',
        PaymentController::class,
        'searchBills',
        [Request::METHOD_GET]
    ),
    new Route(
        '/payments/purchases',
        PaymentController::class,
        'purchases',
        [Request::METHOD_GET]
    ),
    new Route(
        '/payments/purchases/search',
        PaymentController::class,
        'searchPurchases',
        [Request::METHOD_GET]
    ),

    new Route(
        '/product-groups',
        ProductGroupController::class,
        'all',
        [Request::METHOD_GET]
    ),
    new Route(
        '/product-groups/create',
        ProductGroupController::class,
        'create',
        [Request::METHOD_GET, Request::METHOD_POST]
    ),
    new Route(
        '/product-groups/:id',
        ProductGroupController::class,
        'edit',
        [Request::METHOD_GET, Request::METHOD_POST]
    ),
    new Route(
        '/products',
        ProductController::class,
        'all',
        [Request::METHOD_GET]
    ),
    new Route(
        '/products/create',
        ProductController::class,
        'create',
        [Request::METHOD_GET, Request::METHOD_POST]
    ),
    new Route(
        '/products/:id',
        ProductController::class,
        'edit',
        [Request::METHOD_GET, Request::METHOD_POST]
    ),

    new Route(
        '/counters',
        CounterController::class,
        'all',
        [Request::METHOD_GET]
    ),
    new Route(
        '/counters/create',
        CounterController::class,
        'create',
        [Request::METHOD_GET, Request::METHOD_POST]
    ),
    new Route(
        '/counters/:id',
        CounterController::class,
        'edit',
        [Request::METHOD_GET, Request::METHOD_POST]
    ),

    new Route(
        '/counters/:id/actions',
        CounterController::class,
        'counterActions',
        [Request::METHOD_GET, Request::METHOD_POST]
    ),
    new Route(
        '/counters/:id/actions/create',
        CounterController::class,
        'createCounterAction',
        [Request::METHOD_GET, Request::METHOD_POST]
    ),
    new Route(
        '/counters/:id/actions/:id',
        CounterController::class,
        'editCounterAction',
        [Request::METHOD_GET, Request::METHOD_POST]
    ),

    new Route(
        '/users/search',
        UserController::class,
        'search',
        [Request::METHOD_GET]
    ),
    new Route(
        '/users',
        UserController::class,
        'all',
        [Request::METHOD_GET]
    ),
    new Route(
        '/bots',
        BotController::class,
        'all',
        [Request::METHOD_GET]
    ),

    new Route(
        '/logs',
        LogController::class,
        'all',
        [Request::METHOD_GET]
    ),
    new Route(
        '/logs/search',
        LogController::class,
        'search',
        [Request::METHOD_GET]
    ),
];
