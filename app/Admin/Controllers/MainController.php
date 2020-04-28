<?php

namespace Admin\Controllers;

use Admin\Controllers\Shared\AdminController;
use Shared\Core\Http\Request;
use Shared\Core\App;
use Client\Repositories\UserRepository\UserRepository;

class MainController extends AdminController
{
    public function main(Request $request)
    {
        if (!$this->isAuthorized()) {
            $request->redirect('/login');
        }

        /** @var UserRepository $userRepository */
        $userRepository = App::get('user');

        return $this->render([
            'usersCount' => $userRepository->count()
        ]);
    }
}
