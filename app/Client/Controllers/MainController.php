<?php

namespace Client\Controllers;

use Client\Controllers\Shared\SiteController;
use Shared\Core\Http\Request;
use Shared\Core\App;
use Client\Repositories\UserRepository\UserRepository;

class MainController extends SiteController
{
    public function main(Request $request)
    {
        if ($this->isAuthorized) {
            return (new UserController())->search($request);
        }

        /** @var UserRepository $userRepository */
        $userRepository = App::get('user');
        $women = $userRepository->search();

        return $this->render([
            'women' => $women,
        ]);
    }
}
