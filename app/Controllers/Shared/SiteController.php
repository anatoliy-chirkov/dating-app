<?php

namespace Controllers\Shared;

use Core\Controllers\BaseController;
use Core\ServiceContainer;
use Repositories\UserRepository\UserRepository;

abstract class SiteController extends BaseController
{
    protected function render($vars = []): string
    {
        $serviceContainer = ServiceContainer::getInstance();

        $vars['socketUrl']    = $serviceContainer->get('env')->get('SOCKET_URL');
        $vars['title']        = $serviceContainer->get('env')->get('APP_NAME');
        $vars['description']  = $serviceContainer->get('env')->get('APP_DESCRIPTION');

        $isAuthorized  = $this->isAuthorized();

        if ($isAuthorized) {
            $vars['me'] = $serviceContainer->get('auth_service')->getUser();
            $vars['countNotReadMessages'] = $serviceContainer->get('message_repository')->getCountNotReadMessages($me['id']);
            $vars['countNotSeenVisits'] = $serviceContainer->get('visit_repository')->getNotSeenVisitsCount($me['id']);

            /** @var UserRepository $userRepository */
            $userRepository = $serviceContainer->get('user_repository');
            $userRepository->setTemporaryOnline($me['id']);
        } else {
            $vars['me'] = [];
            $vars['countNotReadMessages'] = 0;
        }

        return parent::render($vars);
    }

    protected function isAuthorized(): bool
    {
        return ServiceContainer::getInstance()->get('auth_service')->verifyCookieToken();
    }

    protected function getViewsPath(): string
    {
        return APP_PATH . '/' . self::VIEWS_FOLDER;
    }
}
