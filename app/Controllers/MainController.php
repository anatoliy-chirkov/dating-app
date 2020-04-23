<?php

namespace Controllers;

use Controllers\Shared\SiteController;
use Core\Http\Request;
use Core\ServiceContainer;
use Repositories\UserRepository\UserRepository;

class MainController extends SiteController
{
    public function main(Request $request)
    {
        /** @var UserRepository $userRepository */
        $userRepository = ServiceContainer::getInstance()->get('user_repository');
        $men = $userRepository->search($request->get('sex', ['man']));
        $women = $userRepository->search($request->get('sex', ['woman']));

        return $this->render([
            'men' => $men,
            'women' => $women,
        ]);
    }
}
