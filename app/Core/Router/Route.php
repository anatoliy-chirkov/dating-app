<?php

namespace Core\Router;

class Route
{
    private const
        EMBEDDED_PARAMS_REGEX        = '/:[a-zA-Z]+/',
        EMBEDDED_PARAMS_VALUES_REGEX = '[0-9a-z]+'
    ;

    private $uri;
    private $controllerClass;
    private $actionName;
    private $availableMethods;

    public function __construct(string $uri, string $controllerClass, string $actionName, array $availableMethods)
    {
        $this->uri              = $uri;
        $this->controllerClass  = $controllerClass;
        $this->actionName       = $actionName;
        $this->availableMethods = $availableMethods;
    }

    public function getRouteRegularExpression()
    {
        $regularExpression = '/^' . str_replace('/', '\/', $this->uri) . '$/';

        $embeddedParams = $this->getRawEmbeddedParams();

        if (!empty($embeddedParams)) {
            foreach ($embeddedParams as $embeddedParam) {
                $regularExpression = str_replace(
                    $embeddedParam,
                    self::EMBEDDED_PARAMS_VALUES_REGEX,
                    $regularExpression
                );
            }
        }

        return $regularExpression;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getAvailableMethods()
    {
        return $this->availableMethods;
    }

    public function getControllerClass()
    {
        return $this->controllerClass;
    }

    public function getActionName()
    {
        return $this->actionName;
    }

    private function getRawEmbeddedParams()
    {
        $matches = [];
        preg_match_all(self::EMBEDDED_PARAMS_REGEX, $this->uri, $matches);

        return array_pop($matches);
    }
}
