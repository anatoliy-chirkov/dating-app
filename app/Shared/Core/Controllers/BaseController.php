<?php

namespace Shared\Core\Controllers;

use Shared\Core\App;
use Shared\Core\Controllers\Exceptions\ForbiddenException;

abstract class BaseController
{
    protected const VIEWS_FOLDER = 'Views';

    public function __call($name, $arguments)
    {
        if ($this instanceof IProtected && in_array($this->getRealMethodName($name), $this->getProtectedMethods())) {

            if (!$this->isAuthorized()) {
                throw new ForbiddenException();
            }
        }

        $realActionName = $this->getRealMethodName($name);

        return $this->$realActionName(...$arguments);
    }

    abstract protected function isAuthorized(): bool;
    abstract protected function getViewsPath(): string;

    protected function renderJson(array $response)
    {
        header('Content-Type: application/json');

        echo json_encode($response);
        die;
    }

    protected function render($vars = []): string
    {
        $isAuthorized  = $this->isAuthorized();
        $innerViewPath = $this->getViewsPath() . '/' . $this->getViewFolderName() . '/' . $this->getViewName();
        $notification = App::get('notificationService')->flush();

        foreach ($vars as $varName => $varValue) {
            $$varName = $varValue;
        }

        unset($vars);

        return require_once $this->getViewsPath() . '/' . $this->getLayoutName();
    }

    protected function getLayoutName(): string
    {
        return 'layout.php';
    }

    private function getViewName()
    {
        $backtrace = debug_backtrace();
        $functionName = $backtrace[2]['function'] !== 'render' ? $backtrace[2]['function'] : $backtrace[3]['function'];
        return $functionName  . '.php';
    }

    private function getViewFolderName()
    {
        return str_replace('Controller', '', array_pop(explode('\\', static::class)));
    }

    private function getRealMethodName(string $fixtureName)
    {
        return str_replace(ICatchMethods::CATCH_METHOD_PREFIX, '', $fixtureName);
    }
}
