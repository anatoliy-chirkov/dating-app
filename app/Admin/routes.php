<?php

use Shared\Core\Http\IMethod;
use Shared\Core\Router\Route;
use Admin\Controllers\{MainController, AuthController, PaymentController, ProductController, UserController,
    BotController, LogController, CounterController, ProductGroupController};

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
        '/payments/bills/search',
        PaymentController::class,
        'searchBills',
        [IMethod::GET]
    ),
    new Route(
        '/payments/purchases',
        PaymentController::class,
        'purchases',
        [IMethod::GET]
    ),
    new Route(
        '/payments/purchases/search',
        PaymentController::class,
        'searchPurchases',
        [IMethod::GET]
    ),

    new Route(
        '/product-groups',
        ProductGroupController::class,
        'all',
        [IMethod::GET]
    ),
    new Route(
        '/product-groups/create',
        ProductGroupController::class,
        'create',
        [IMethod::GET, IMethod::POST]
    ),
    new Route(
        '/product-groups/:id',
        ProductGroupController::class,
        'edit',
        [IMethod::GET, IMethod::POST]
    ),
    new Route(
        '/products',
        ProductController::class,
        'all',
        [IMethod::GET]
    ),
    new Route(
        '/products/create',
        ProductController::class,
        'create',
        [IMethod::GET, IMethod::POST]
    ),
    new Route(
        '/products/:id',
        ProductController::class,
        'edit',
        [IMethod::GET, IMethod::POST]
    ),

    new Route(
        '/counters',
        CounterController::class,
        'all',
        [IMethod::GET]
    ),
    new Route(
        '/counters/create',
        CounterController::class,
        'create',
        [IMethod::GET, IMethod::POST]
    ),
    new Route(
        '/counters/:id',
        CounterController::class,
        'edit',
        [IMethod::GET, IMethod::POST]
    ),

    new Route(
        '/counters/:id/actions',
        CounterController::class,
        'counterActions',
        [IMethod::GET, IMethod::POST]
    ),
    new Route(
        '/counters/:id/actions/create',
        CounterController::class,
        'createCounterAction',
        [IMethod::GET, IMethod::POST]
    ),
    new Route(
        '/counters/:id/actions/:id',
        CounterController::class,
        'editCounterAction',
        [IMethod::GET, IMethod::POST]
    ),

    new Route(
        '/users/search',
        UserController::class,
        'search',
        [IMethod::GET]
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
    new Route(
        '/logs/search',
        LogController::class,
        'search',
        [IMethod::GET]
    ),
];
