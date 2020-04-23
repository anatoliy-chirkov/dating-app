<?php

namespace Admin\Controllers;

use Admin\Controllers\Shared\AdminController;
use Core\ServiceContainer;
use Repositories\UserRepository\UserRepository;

class UserController extends AdminController
{
    public function all()
    {
        /** @var UserRepository $userRepository */
        $userRepository = ServiceContainer::getInstance()->get('user_repository');

        return $this->render([
            'users' => $userRepository->search()
        ]);
    }
}
