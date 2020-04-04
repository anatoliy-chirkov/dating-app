<?php

namespace Core\Controllers;

use Core\Controllers\Exceptions\ForbiddenException;
use Core\ServiceContainer;
use Services\AuthService;
use Services\NotificationService\Notification;

abstract class BaseController
{
    public function __call($name, $arguments)
    {
        if ($this instanceof IProtected && in_array($this->getRealMethodName($name), $this->getProtectedMethods())) {
            /** @var AuthService $authService */
            $authService = ServiceContainer::getInstance()->get('auth_service');

            if (!$authService->verifyCookieToken()) {
                throw new ForbiddenException();
            }
        }

        $realActionName = $this->getRealMethodName($name);

        return $this->$realActionName(...$arguments);
    }

    protected function renderJson(array $response)
    {
        header('Content-Type: application/json');

        echo json_encode($response);
    }

    protected function render($vars = []): string
    {
        $serviceContainer = ServiceContainer::getInstance();

        $innerViewPath = $this->getViewPath();
        $title         = $serviceContainer->get('env')->get('APP_NAME');
        $description   = $serviceContainer->get('env')->get('APP_DESCRIPTION');
        /** @var Notification $notification */
        $notification  = $serviceContainer->get('notification_service')->flush();
        $isAuthorized  = $serviceContainer->get('auth_service')->verifyCookieToken();

        if ($isAuthorized) {
            $me = $serviceContainer->get('auth_service')->getUser();
            $countNotReadMessages = $serviceContainer->get('message_repository')->getCountNotReadMessages($me['id']);
        } else {
            $me = [];
            $countNotReadMessages = 0;
        }

        foreach ($vars as $varName => $varValue) {
            $$varName = $varValue;
        }

        unset($vars);
        unset($serviceContainer);

        return require_once APP_PATH . '/Views/layout.php';
    }

    /**
     * @return string
     */
    private function getViewPath(): string
    {
        $backtrace = debug_backtrace();
        $folderName = str_replace('Controller', '', array_pop(explode('\\', static::class)));
        $viewName = $backtrace[2]['function'] . '.php';

        return APP_PATH . '/' . 'Views' . '/' . $folderName . '/' . $viewName;
    }

    private function getRealMethodName(string $fixtureName)
    {
        return str_replace(ICatchMethods::CATCH_METHOD_PREFIX, '', $fixtureName);
    }
}
