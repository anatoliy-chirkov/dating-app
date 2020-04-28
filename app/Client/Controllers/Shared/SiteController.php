<?php

namespace Client\Controllers\Shared;

use Shared\Core\Controllers\BaseController;
use Shared\Core\App;
use Client\Repositories\UserRepository\UserRepository;
use Client\Services\ActionService\Action;
use Client\Services\ActionService\IAction;

abstract class SiteController extends BaseController
{
    final protected function render($vars = []): string
    {
        $vars['socketUrl']    = App::get('env')->get('SOCKET_URL');
        $vars['title']        = App::get('env')->get('APP_NAME');
        $vars['description']  = App::get('env')->get('APP_DESCRIPTION');

        $isAuthorized  = $this->isAuthorized();

        if ($isAuthorized) {
            $vars['me'] = App::get('authService')->getUser();
            $vars['countNotReadMessages'] = App::get('message')->getCountNotReadMessages($vars['me']['id']);
            $vars['countNotSeenVisits'] = App::get('visit')->getNotSeenVisitsCount($vars['me']['id']);

            if (
                !Action::hasRestrictedProduct(IAction::HIDE_ONLINE)
                || !Action::check(IAction::HIDE_ONLINE, $vars['me']['id'])
            ) {
                /** @var UserRepository $userRepository */
                $userRepository = App::get('user');
                $userRepository->setTemporaryOnline($vars['me']['id']);
            }
        } else {
            $vars['me'] = [];
            $vars['countNotReadMessages'] = 0;
        }

        return parent::render($vars);
    }

    final protected function isAuthorized(): bool
    {
        return App::get('authService')->verifyCookieToken();
    }

    final protected function getViewsPath(): string
    {
        return CLIENT_PATH . '/' . self::VIEWS_FOLDER;
    }
}
