<?php

namespace Admin\Controllers;

use Admin\Controllers\Shared\AdminController;
use Core\Controllers\IProtected;
use Core\Http\Request;
use Core\ServiceContainer;
use Repositories\UserRepository\UserRepository;
use Services\IsUserOnlineService;

class UserController extends AdminController implements IProtected
{
    public function getProtectedMethods()
    {
        return ['all'];
    }

    public function all()
    {
        return $this->render();
    }

    public function search(Request $request)
    {
        $offset = (int) $request->get('start');
        $length = (int) $request->get('length');
        $page = $offset / $length + 1;

        /** @var UserRepository $userRepository */
        $userRepository = ServiceContainer::getInstance()->get('user_repository');

        $usersCount = $userRepository->count();
        $usersHTML = [];

        foreach ($userRepository->search([], null, null, null, null, $page, $length) as $user) {
            $imgLink = str_replace('admin.', '', $_SERVER['HTTP_HOST']) . $user['clientPath'];
            /** @var IsUserOnlineService $isOnlineService */
            $isOnlineService = ServiceContainer::getInstance()->get('is_user_online_service');
            $profileLink = str_replace('admin.', '', $_SERVER['HTTP_HOST']) . '/user/' . $user['id'];

            // COLUMNS VIEWS
            $name = <<<HTML
                <img src="http://{$imgLink}" alt="Avatar">
                <span>{$user['name']}, {$user['age']}</span>
                <span class="cell-detail-description">{$user['city']}</span>
HTML;
            $status = $isOnlineService->getViewElement($user['sex'], $user['isConnected'], $user['lastConnected']);
            $buttons = <<<HTML
                <div class="btn-group btn-hspace">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">More <span class="icon-dropdown mdi mdi-chevron-down"></span></button>
                    <div class="dropdown-menu" role="menu" style="">
                        <a class="dropdown-item" target="_blank" href="http://{$profileLink}">Open profile</a>
                    </div>
                </div>
HTML;

            $usersHTML[] = [
                $name,
                $status,
                $buttons
            ];
        }

        $this->renderJson([
            'draw' => (int) $request->get('draw'),
            'recordsTotal' => $usersCount,
            'recordsFiltered' => $usersCount,
            'data' => $usersHTML,
        ]);
    }
}
