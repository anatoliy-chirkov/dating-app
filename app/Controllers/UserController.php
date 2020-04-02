<?php

namespace Controllers;

use Core\Controllers\BaseController;
use Core\Http\Request;
use Core\ServiceContainer;
use Repositories\UserRepository\UserRepository;
use Services\AuthService;

class UserController extends BaseController
{
    public function search(Request $request)
    {
        /** @var UserRepository $userRepository */
        $userRepository = ServiceContainer::getInstance()->get('user_repository');
        $users = $userRepository->search(
            $request->get('sex', []),
            $request->get('ageFrom'),
            $request->get('ageTo'),
            $request->get('city'),
            $request->get('page', 1)
        );
        $count = $userRepository->count(
            $request->get('sex', []),
            $request->get('ageFrom'),
            $request->get('ageTo'),
            $request->get('city')
        );

        $pages = ceil($count / 20);

        return $this->render([
            'users' => $users,
            'sex' => $request->get('sex', []),
            'ageFrom' => $request->get('ageFrom', ''),
            'ageTo' => $request->get('ageTo', ''),
            'city' => $request->get('city', ''),
            'page' => $request->get('page', 1),
            'pages' => $pages,
        ]);
    }

    public function getOne(Request $request, $userId)
    {
        /** @var UserRepository $userRepository */
        $userRepository = ServiceContainer::getInstance()->get('user_repository');
        $user = $userRepository->getById($userId);

        /** @var AuthService $authService */
        $authService = ServiceContainer::getInstance()->get('auth_service');
        $me = $authService->getUser();

        return $this->render(['user' => $user, 'isMe' => $me['id'] === $user['id']]);
    }
}
