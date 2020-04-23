<?php

use Core\Http\IMethod;
use Core\Router\Route;
use Admin\Controllers\{MainController, AuthController};

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
];
