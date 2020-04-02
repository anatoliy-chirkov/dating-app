<?php

namespace Core\Router;

use Core\Http\Request;
use Core\Router\Exceptions\IncorrectRouteConfigurationException;
use Core\Router\Exceptions\PageNotFoundException;

class Matcher
{
    public function match(Request $request, array $routes)
    {
        /** @var Route $route */
        foreach ($routes as $route) {
            if (!$route instanceof Route) {
                throw new IncorrectRouteConfigurationException();
            }

            if ($this->isRouteMatch($request, $route)) {
                return $route;
            }
        }

        throw new PageNotFoundException();
    }

    private function isRouteMatch(Request $request, Route $route)
    {
        return $this->isMethodMatch($request->getMethod(), $route)
            && $this->isUriMatch($request->getUri(), $route);
    }

    private function isMethodMatch($requestMethod, Route $route)
    {
        return in_array($requestMethod, $route->getAvailableMethods());
    }

    private function isUriMatch($requestUri, Route $route)
    {
        $matches = [];
        preg_match($route->getRouteRegularExpression(), $requestUri, $matches);

        return count($matches) > 0;
    }
}
