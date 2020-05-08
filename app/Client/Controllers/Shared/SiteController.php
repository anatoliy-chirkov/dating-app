<?php

namespace Client\Controllers\Shared;

use Client\Services\LangService\Resolver;
use Shared\Core\Controllers\BaseController;
use Shared\Core\App;
use Client\Repositories\UserRepository\UserRepository;
use Client\Services\ActionService\Action;
use Client\Services\ActionService\IAction;

abstract class SiteController extends BaseController
{
    protected $isAuthorized;
    protected $user = [];

    public function __construct()
    {
        if (isset($_GET['lang'])) {
            Resolver::setLang($_GET['lang']);
        }

        $this->isAuthorized = $this->isAuthorized();

        if ($this->isAuthorized) {
            $this->user = App::get('authService')->getUser();
        }
    }

    final protected function render($vars = []): string
    {
        $vars['socketUrl']    = App::get('env')->get('SOCKET_URL');
        $vars['title']        = App::get('env')->get('APP_NAME');
        $vars['description']  = App::get('env')->get('APP_DESCRIPTION');

        if ($this->isAuthorized) {
            $vars['me'] = $this->user;
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

    protected function getLayoutName(): string
    {
        return $this->isAuthorized ? parent::getLayoutName() : 'publicLayout.php';
    }
}
