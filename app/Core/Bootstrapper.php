<?php

namespace Core;

use Core\Controllers\ICatchMethods;
use Core\Http\Request;
use Core\Router\Matcher;
use Core\Router\Route;

class Bootstrapper
{
    public function bootstrap()
    {
        $request      = $this->getRequest();
        $routeMatcher = $this->getRouteMatcher();
        $routes       = $this->getRoutes();

        /** @var Route $route */
        $route = $routeMatcher->match($request, $routes);

        $controllerClass = $route->getControllerClass();
        $action          = $route->getActionName();

        $embeddedUriParams = $this->getEmbeddedUriParams($request, $route);

        $controller = new $controllerClass;

        if ($controller instanceof ICatchMethods) {
            $this->transformActionName($action);
        }

        return empty($embeddedUriParams)
            ? $controller->$action($request)
            : $controller->$action($request, ...$embeddedUriParams)
        ;
    }

    private function transformActionName(string &$action)
    {
        $action = ICatchMethods::CATCH_METHOD_PREFIX . $action;
    }

    private function getEmbeddedUriParams(Request $request, Route $route)
    {
        $requestUriArray = explode('/', $request->getUri());
        $routeUriArray = explode('/', $route->getUri());

        return array_diff_assoc(
            $requestUriArray,
            $routeUriArray
        );
    }

    private function getRouteMatcher(): Matcher
    {
        return ServiceContainer::getInstance()->get('route_matcher');
    }

    private function getRequest(): Request
    {
        return ServiceContainer::getInstance()->get('request');
    }

    private function getRoutes(): array
    {
        return ServiceContainer::getInstance()->get('routes');
    }
}
